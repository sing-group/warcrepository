<?php

namespace App\Support\Api;

/**
 * Sustituye a Dingo\Api\Routing\Helpers: da acceso a $this->response
 * desde los controladores de la API.
 */
trait Helpers
{
    /**
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if ($key === 'response') {
            return app(ResponseFactory::class);
        }

        throw new \ErrorException('Undefined property '.get_class($this).'::'.$key);
    }
}
