<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Sustituye al middleware 'api.auth' de Dingo con su proveedor JWT,
 * con los mismos mensajes y códigos de error.
 */
class ApiAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $this->getToken($request);

        if (! $token) {
            throw new UnauthorizedHttpException('dingo', 'Failed to authenticate because of bad credentials or an invalid authorization header.');
        }

        try {
            if (! $user = JWTAuth::setToken($token)->authenticate()) {
                throw new UnauthorizedHttpException('JWTAuth', 'Unable to authenticate with invalid token.');
            }
        } catch (JWTException $exception) {
            throw new UnauthorizedHttpException('JWTAuth', $exception->getMessage(), $exception);
        }

        return $next($request);
    }

    /**
     * Token from the "Authorization: Bearer" header or, failing that, the "token" query parameter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|false
     */
    private function getToken(Request $request)
    {
        $header = (string) $request->headers->get('authorization');

        if (Str::startsWith(strtolower($header), 'bearer')) {
            return trim(str_ireplace('bearer', '', $header));
        }

        return $request->query('token', false);
    }
}
