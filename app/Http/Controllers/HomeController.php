<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\User;
use App\Corpus;
class HomeController extends Controller{

    /**
     * Download WARCProcessor-4.2.2_beta-SETUP.jar file from public/warc-processor directory
     */
    public function downloadwarcprocessor(){
        \DB::table('warcprocessor_downloads')->where('id', 1)->increment('downloads_counter', 1);
        return response()->download(public_path('warc-processor/WARCProcessor-4.3.0.jar'));
    }

    /**
     * return the welcome page passing the required variables to complete the graphics
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index(){
        if (Auth::check()) {
            return redirect('/user/home');
        }else{
            $num_users =  User::get()->count();
            $num_sites = $this->calculateTotalSites();
            $warcprocessor_downloads = \DB::table('warcprocessor_downloads')->where('id', 1)->first()->downloads_counter;
            $num_corpus = Corpus::get()->count();
            $spamHamTable = $this->calculateSpamHamRatio();
            \Lava::PieChart('spamHamRatio', $spamHamTable, [
                'title'  => 'Spam/Ham ratio',
                'is3D'   => true,
                'slices' => [
                    ['offset' => 0.2],
                    ['offset' => 0.25],
                    ['offset' => 0.3]
                ]
            ]);
            $contentTypesTable = $this->calculateContentTypeRatio();
            \Lava::PieChart('contentTypeRatio', $contentTypesTable, [
                'title'  => 'Content-Types',
                'is3D'   => true,
                'slices' => [
                    ['offset' => 0.2],
                    ['offset' => 0.25],
                    ['offset' => 0.3]
                ]
            ]);

            $contentLanguagesTable = $this->calculateLanguageRatio();
            \Lava::PieChart('languagesRatio', $contentLanguagesTable, [
                'title'  => 'Content Languages',
                'is3D'   => true,
                'slices' => [
                    ['offset' => 0.2],
                    ['offset' => 0.25],
                    ['offset' => 0.3]
                ]
            ]);
            return view('welcome', compact(['warcprocessor_downloads', 'num_users', 'num_corpus', 'num_sites']));
        }
    }

    /**
     * calculate the datatable which contains the spam/ham ratio
     * @return mixed
     */
    public function calculateSpamHamRatio(){
        $spamHamTable = \Lava::DataTable();
        $corpus = Corpus::all();
        $spam_amount = 0;
        $total_sites = 0;
        foreach($corpus as $c){
            $spam_amount += $c->total_sites * $c->spam_amount / 100;
            $total_sites += $c->total_sites;
        }
        $spamHamTable->addStringColumn('Reasons')
            ->addNumberColumn('Percent')
            ->addRow(['Spam', $spam_amount])
            ->addRow(['Ham', $total_sites - $spam_amount]);
        return $spamHamTable;
    }

    /**
     * calculate the datatable which contains the languages distribution
     * @return mixed
     */
    public function calculateLanguageRatio(){
        $languagesTable = \Lava::DataTable();
        $corpus = Corpus::all();
        $languages = array();
        $languageCounter = array();
        foreach($corpus as $c){
            $languages = Corpus::getAllContentLanguagesArray($c);
            foreach ($languages as $lang){
                if(array_key_exists($lang,$languageCounter)){
                    $languageCounter[$lang] += 1;
                }else{
                    $languageCounter[$lang] = 1;
                }
            }
        }
        $languagesTable->addStringColumn('Reasons')
            ->addNumberColumn('Percent');
        foreach ($languageCounter as $lang => $num){
            $languagesTable->addRow([$lang, $num]);
        }
        return $languagesTable;
    }

    /**
     * calculate total sites of all corpus
     * @return int
     */
    public function calculateTotalSites(){
        $corpus = Corpus::all();
        $total = 0;
        foreach($corpus as $c){
            $total += $c->total_sites;
        }
        return $total;
    }

    /**
     * calculate the datatable which contains the content-types distribution
     * @return mixed
     */
    public function calculateContentTypeRatio(){
        $content_typesTable = \Lava::DataTable();
        $corpus = Corpus::all();
        $content_types = array();
        $contentTypesCounter = array();
        foreach($corpus as $c){
            $content_types = Corpus::getAllContentTypesArray($c);
            foreach ($content_types as $type){
                if(array_key_exists($type,$contentTypesCounter)){
                    $contentTypesCounter[$type] += 1;
                }else{
                    $contentTypesCounter[$type] = 1;
                }
            }
        }
        $content_typesTable->addStringColumn('Reasons')
            ->addNumberColumn('Percent');
        foreach ($contentTypesCounter as $type => $num){
            $content_typesTable->addRow([$type, $num]);
        }
        return $content_typesTable;
    }

    /**
     * allows to download a corpus
     * @param $corpus_uuid the uuid of the corpus you want to download
     */
    public function downloadCorpus($corpus_uuid){
        $corpus = \DB::table('corpus')->where('uuid', $corpus_uuid)->get()[0];
        \DB::table('corpus')->where('uuid', $corpus_uuid)->increment('downloads', 1);
        $files = glob($corpus->path);
        \Zipper::make(public_path('tempZippedCorpus/'.$corpus_uuid.$corpus->name.'.zip'))->add($files)->close();
        chmod(public_path('tempZippedCorpus/'.$corpus_uuid.$corpus->name.'.zip'),0777);
        return response()->download(public_path('tempZippedCorpus/'.$corpus_uuid.$corpus->name.'.zip'))->deleteFileAfterSend(true);
    }

    /**
     * return view common.info this method is called when a user registers
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info(){
        return view('common.info');
    }

}
