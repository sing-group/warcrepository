# WARC-Repository

WARC-Repository es una aplicación web para publicar, buscar y compartir corpus de spam web en formato WARC. Los corpus se generan con WARCProcessor. La aplicación la desarrolla el grupo SING de la Escuela Superior de Ingeniería Informática (ESEI) de la Universidade de Vigo.

## Funcionalidades

- Registro de usuarios con verificación por correo, inicio de sesión y recuperación de contraseña.
- Subida de corpus desde WARCProcessor a través de una API REST autenticada con JWT.
- Al subir un corpus, se extraen automáticamente sus metadatos: idiomas, tipos de contenido, fechas, número de sitios y porcentaje de spam.
- Gestión de corpus propios: públicos, privados, papelera y edición de nombre y descripción.
- Búsqueda de corpus por usuario, tamaño y porcentaje de spam.
- Combinación de varios corpus en uno nuevo, filtrando por idioma, tipo de contenido, fecha y spam/ham.
- Descarga de corpus en formato zip.
- Portada con estadísticas globales: usuarios, corpus, sitios, descargas y gráficos de distribución.
- Panel de administración de usuarios.
- Interfaz en inglés y español.

## Tecnologías

| | |
|---|---|
| Backend | PHP 8.4, Laravel 13 |
| API | Rutas propias con Fractal y `php-open-source-saver/jwt-auth` |
| Frontend | Blade, Bootstrap 3, jQuery 3, Vue 2.7, compilados con Vite 7 |
| Base de datos | MySQL / MariaDB |
| Procesado de corpus | Java (`storage/java/*.jar`) |

## API

Todas las rutas cuelgan de `/api`:

| Método | Ruta | Autenticación | Descripción |
|---|---|---|---|
| POST | `/api/login` | — | Devuelve un token JWT (`email`, `password`). |
| GET | `/api/token` | Token | Renueva el token. |
| POST | `/api/user` | — | Crea un usuario. |
| GET | `/api/me` | Token | Datos del usuario actual. |
| GET | `/api/users` | Token | Lista de usuarios. |
| POST | `/api/corpus` | Token | Sube un corpus en zip (campo `file`). |

El token se envía en la cabecera `Authorization: Bearer <token>`.

## Requisitos

- PHP 8.3 o superior, con las extensiones `mbstring`, `xml`, `zip`, `pdo_mysql`, `curl` y `openssl`.
- Composer 2.
- Node.js 20.19 o superior, con npm.
- Java (JRE).
- MySQL o MariaDB.

## Instalación

```sh
cp .env.example .env              # configura la base de datos, el correo, etc.
composer install --no-dev --optimize-autoloader
php artisan key:generate          # solo en una instalación nueva
npm ci && npm run build
php artisan migrate               # solo en una base de datos nueva
php artisan optimize
```

En los contenedores Docker, `afterstart.sh` hace estos pasos al arrancar. Solo ejecuta los lentos (Composer, npm, permisos) cuando hace falta.

## Tests

```sh
composer install
php artisan test
```

Los tests usan SQLite en memoria y no tocan la base de datos real.

## Historial de versiones

- **Rama `main`**: versión actual, con Laravel 13 y PHP 8.4.
- **Rama `laravel-5.3`**: versión original, con Laravel 5.3 y PHP 7.0.

### Migración a Laravel 13 (octubre de 2026)

La aplicación se ha migrado de Laravel 5.3 y PHP 7.0 a Laravel 13 y PHP 8.4, sin cambios de funcionalidad. Se conservan las URLs, la API (formato de respuestas y validez de los tokens JWT ya emitidos) y el esquema de la base de datos.

Los paquetes abandonados se han sustituido por alternativas mantenidas o por código propio:

- `dingo/api` → rutas propias.
- `tymon/jwt-auth` → `php-open-source-saver/jwt-auth`.
- `chumper/zipper` → `ZipArchive`.
- `khill/lavacharts` → Google Charts.
- `styde/html` → `rdx/laravelcollective-html`.
- Gulp y Laravel Elixir → Vite.

Los detalles están en [MIGRACION.md](MIGRACION.md).

> La migración se ha realizado con la asistencia de **Claude** (Anthropic), un asistente de IA, bajo la supervisión y revisión del equipo de desarrollo.
