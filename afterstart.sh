#!/bin/bash
# Preparación de la app (Laravel 13) dentro del contenedor.
#cd "$(dirname "$0")" || exit 1

# Dependencias PHP según composer.lock (ya incluye jwt-auth, uuid, Form, etc.)
composer install --no-dev --optimize-autoloader --no-interaction

# Solo si el .env no tiene clave: cambiarla invalidaría las sesiones activas.
if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi
# No se genera secreto JWT: se mantiene el de antes para que los tokens
# de los clientes de la API sigan siendo válidos (ver config/jwt.php).

# Assets con Vite (necesita Node 20.19 o superior en el contenedor).
if command -v npm > /dev/null; then
    npm ci
    npm run build
else
    echo "AVISO: npm no está instalado; no se han compilado los assets (public/build)." >&2
fi

php artisan optimize

#These are to initialize database but database is automatically restored from sql
#php artisan migrate
#php artisan db:seed
chmod 777 -R storage
chmod 777 -R public/images
chmod 777 -R public/unzippedCorpus
mkdir -p public/tempZippedCorpus
chmod 777 -R public/tempZippedCorpus
