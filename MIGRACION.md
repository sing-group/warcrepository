# Migración de WARC-Repository a Laravel 13 y PHP 8.4

Esta carpeta es la app de `warcrepository_producción` portada a **Laravel 13** (la versión actual), compatible con **PHP 8.4** (requiere PHP 8.3 o superior). La funcionalidad, las URLs y la API son las mismas, y la base de datos no cambia.

## Pasos para ponerla en marcha

1. Copia los datos, los recursos estáticos y los `.jar` desde la carpeta original. No los he copiado yo porque son muchos ficheros, datos de producción y ficheros de más de 30 MB:

   ```sh
   cd ~/work/desarrollo/warcrepository
   cp -rn warcrepository_producción/public/. warcrepository_laravel13/public/
   cp -rn warcrepository_producción/storage/app/. warcrepository_laravel13/storage/app/
   cp -rn warcrepository_producción/storage/java warcrepository_laravel13/storage/
   ```

   Usa `-n` para no sobrescribir `public/index.php` y `public/.htaccess`, que son los nuevos de Laravel 13.

2. Crea el `.env` a partir de tu `.env` de producción y renombra estas variables:
   - `MAIL_DRIVER` → `MAIL_MAILER`
   - `CACHE_DRIVER` → `CACHE_STORE`
   - `QUEUE_DRIVER` → `QUEUE_CONNECTION`

   Los nombres antiguos se siguen leyendo como respaldo. Añade también `BCRYPT_ROUNDS=10` (mira `.env.example`). Mantén el mismo `APP_KEY`. Las variables `API_*` de Dingo ya no se usan, salvo `API_DEBUG`.

3. Dentro del contenedor de PHP 8.4, ejecuta `composer install --no-dev`. Si quieres pasar los tests, quita `--no-dev`.

4. No hace falta ejecutar `php artisan migrate`, porque las migraciones son las mismas y ya están aplicadas en la base de datos. Ya no hace falta `php artisan jwt:generate`.

5. Compila los assets con Vite (requiere Node 20.19 o superior, por ejemplo en un contenedor `node:22`):

   ```sh
   npm ci
   npm run build
   ```

   Se generan en `public/build`. Durante el desarrollo, `npm run dev` recarga los cambios en caliente.

6. Ejecuta los tests con `php artisan test`. Usan SQLite en memoria y no tocan la base de datos.

La extensión `zip` de PHP es necesaria, porque se usa `ZipArchive` para descomprimir y descargar corpus. Java sigue siendo necesario para los `.jar` de `storage/java`.

## Qué ha cambiado

| Antes (Laravel 5.3) | Ahora (Laravel 13) |
|---|---|
| `dingo/api` 2.0 alpha | Rutas propias en `routes/api.php`. `app/Support/Api` reproduce las respuestas de Dingo (`{"data": ...}` con Fractal y errores `{"message", "status_code"}`), y `app/Http/Middleware/ApiAuthenticate.php` sustituye a `api.auth`. |
| `tymon/jwt-auth` 0.5 | `php-open-source-saver/jwt-auth` 2.9, con el mismo secreto por defecto, el mismo algoritmo (HS256) y la misma duración. Los tokens que ya tengan los clientes siguen siendo válidos. |
| `webpatser/laravel-uuid` | `ramsey/uuid`, con el mismo formato de UUID. |
| `chumper/zipper` | `app/Support/Zipper.php` (ZipArchive), que genera un zip con la misma estructura. |
| `khill/lavacharts` (incompatible) | `app/Support/Charts`, que dibuja los mismos gráficos de tarta con Google Charts. |
| `styde/html` (Form::) | `rdx/laravelcollective-html`, con el mismo `Form::open()`. |
| Autenticación de Laravel 5.3 | `laravel/ui` aporta los mismos controladores de login, registro y contraseña. |
| `App\Http\Kernel`, `RouteServiceProvider` | `bootstrap/app.php` (estructura de Laravel 11+). |
| `resources/lang` | `lang/` |
| Gulp 3 + Laravel Elixir (solo con Node ≤ 10) | Vite 7 (`vite.config.js`), que compila los mismos `resources/assets/sass/app.scss` y `resources/assets/js/app.js` con Bootstrap 3 (`bootstrap-sass` 3.4), jQuery 3, Vue 2.7, lodash y axios. `layouts/app.blade.php` carga el JS con `@vite` en vez de `asset('js/app.js')`. |
| `database/seeds` | `database/seeders`, con espacio de nombres; `ModelFactory.php` pasa a ser `UserFactory` y `CorpusFactory`. |

Otros ajustes para conservar el comportamiento:
- Las fechas en el JSON siguen saliendo como `Y-m-d H:i:s`.
- La conexión MySQL usa `utf8` / `utf8_unicode_ci`.
- El disco `local` vuelve a ser `storage/app`.
- La cookie de sesión sigue llamándose `laravel_session`.
- El enlace del correo de recuperar contraseña tiene el mismo formato (`/password/reset/{token}`).
- `CorpusTransformer.php` empezaba por `<?` en vez de `<?php`. Está corregido.
- La ruta `/registration` se declaraba de una forma que rompe la caché de rutas de Laravel 13 (`php artisan optimize`). Ahora se declara con `->name('registration')`, con el mismo resultado.
- `afterstart.sh` está adaptado: usa `composer install` (respeta `composer.lock`), solo genera `APP_KEY` si falta, ya no regenera el secreto JWT (eso invalidaría los tokens de los clientes), compila con Vite si hay `npm` y ejecuta `php artisan optimize` al final.

## Comprobado

- 22 tests (API y pantallas) pasan en PHP 8.4.27 y 8.3, sin avisos de funciones obsoletas.
- Prueba completa contra un servidor real: login por la API, subida de un corpus (descompresión y `corpusDataExtractor.jar`) y descarga del corpus en zip.
- Un token generado como lo hacía la versión antigua sigue funcionando en `/api/me`.
- El build de Vite funciona y, en un navegador real (Chromium), las páginas con `layouts.app` cargan jQuery 3.7.1, los plugins de Bootstrap 3.4.1 y Vue 2.7.16 montado en `#app`, con la cabecera CSRF de axios y sin errores en la consola.

## Cosas que ya fallaban antes y he dejado igual

- `/user/admin` da error 500, porque la vista espera `$users` y la ruta no se lo pasa. Al administrador le funciona `/user/home`.
- `POST /api/user` es público y deja asignar cualquier campo, incluidos `role` y `verified`, así que cualquiera puede crearse un usuario administrador. No lo he cambiado para no romper la API, pero conviene revisarlo.
- `GET /register` (formulario de registro de Laravel) da error porque no existe la vista `auth.register`. El registro real está en `/registration`.

## Notas sobre los assets

- El resto de vistas siguen cargando sus CSS y JS directamente desde `public/` (`css/bootstrap.min.css`, `js/jquery.js`…), igual que antes. Vite solo afecta a `layouts/app.blade.php`, que era la única que usaba el JS compilado.
- El CSS compilado (`app.scss`) tampoco lo enlazaba ninguna vista antes. Se sigue compilando, pero no lo he añadido a ninguna página para no cambiar su aspecto.
- Bootstrap pasa de 3.3.7 a 3.4.1, la última versión de la rama 3, que corrige fallos de seguridad (XSS).
- Vue 2 ya no tiene mantenimiento. El componente de ejemplo no se usa en ninguna vista, así que se podría quitar más adelante.
- `public/js/app.js` y `public/css/app.css`, los que generaba Elixir, ya no se usan. Puedes borrarlos tras copiar `public/`.
