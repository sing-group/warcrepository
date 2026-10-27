<?php

namespace App\Support\Api;

use Illuminate\Http\JsonResponse;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item as FractalItem;
use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Respuestas con el mismo formato que el ResponseFactory de Dingo 2.0
 * (Fractal con DataArraySerializer: {"data": ...}).
 */
class ResponseFactory
{
    /**
     * @param  mixed  $item
     * @param  \League\Fractal\TransformerAbstract  $transformer
     * @return \Illuminate\Http\JsonResponse
     */
    public function item($item, TransformerAbstract $transformer)
    {
        return $this->fractal(new FractalItem($item, $transformer));
    }

    /**
     * @param  iterable  $collection
     * @param  \League\Fractal\TransformerAbstract  $transformer
     * @return \Illuminate\Http\JsonResponse
     */
    public function collection($collection, TransformerAbstract $transformer)
    {
        return $this->fractal(new FractalCollection($collection, $transformer));
    }

    /**
     * @param  array  $array
     * @return \Illuminate\Http\JsonResponse
     */
    public function withArray(array $array)
    {
        return new JsonResponse($array);
    }

    /**
     * @param  string  $message
     * @param  int  $statusCode
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function error($message, $statusCode)
    {
        throw new HttpException($statusCode, $message);
    }

    /**
     * @param  string  $message
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function errorNotFound($message = 'Not Found')
    {
        $this->error($message, 404);
    }

    /**
     * @param  string  $message
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function errorMethodNotAllowed($message = 'Method Not Allowed')
    {
        $this->error($message, 405);
    }

    /**
     * @param  string  $message
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function errorInternal($message = 'Internal Error')
    {
        $this->error($message, 500);
    }

    /**
     * Dingo no tenía este método: su __call lo convertía en un error 500.
     *
     * @param  string  $message
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function errorUserNotFound($message = 'User not found')
    {
        $this->error($message, 500);
    }

    /**
     * @param  \League\Fractal\Resource\ResourceInterface  $resource
     * @return \Illuminate\Http\JsonResponse
     */
    private function fractal($resource)
    {
        return new JsonResponse((new Manager)->createData($resource)->toArray());
    }
}
