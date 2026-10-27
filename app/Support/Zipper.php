<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use ZipArchive;

/**
 * Sustituye a chumper/zipper (abandonado) para el único uso de la app:
 * Zipper::make($zip)->add($paths)->close().
 *
 * Igual que chumper/zipper, el contenido de cada directorio añadido se
 * guarda en la raíz del zip, conservando sus subdirectorios.
 */
class Zipper
{
    /** @var \ZipArchive */
    private $zip;

    /** @var string */
    private $currentFolder = '';

    /**
     * @param  string  $pathToFile
     * @return static
     */
    public static function make($pathToFile)
    {
        $zipper = new static;
        $zipper->zip = new ZipArchive;
        $zipper->zip->open($pathToFile, ZipArchive::CREATE);

        return $zipper;
    }

    /**
     * @param  string|array  $pathToAdd
     * @param  string|null  $fileName
     * @return $this
     */
    public function add($pathToAdd, $fileName = null)
    {
        if (is_array($pathToAdd)) {
            foreach ($pathToAdd as $key => $path) {
                is_int($key) ? $this->add($path) : $this->add($path, $key);
            }
        } elseif (is_file($pathToAdd)) {
            $this->addFile($pathToAdd, $fileName);
        } else {
            $this->addDir($pathToAdd);
        }

        return $this;
    }

    public function close()
    {
        $this->zip->close();
    }

    /**
     * @param  string  $pathToDir
     */
    private function addDir($pathToDir)
    {
        foreach (File::files($pathToDir) as $file) {
            $this->addFile($pathToDir.'/'.$file->getFilename());
        }

        foreach (File::directories($pathToDir) as $dir) {
            $oldFolder = $this->currentFolder;
            $this->currentFolder = $this->currentFolder === '' ? basename($dir) : $this->currentFolder.'/'.basename($dir);
            $this->addDir($pathToDir.'/'.basename($dir));
            $this->currentFolder = $oldFolder;
        }
    }

    /**
     * @param  string  $pathToAdd
     * @param  string|null  $fileName
     */
    private function addFile($pathToAdd, $fileName = null)
    {
        $fileName = $fileName ?: basename($pathToAdd);
        $internalPath = $this->currentFolder === '' ? '' : $this->currentFolder.'/';

        $this->zip->addFile($pathToAdd, $internalPath.$fileName);
    }
}
