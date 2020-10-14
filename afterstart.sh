#!/bin/bash
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan optimize
docker-compose exec app composer update --no-scripts
docker-compose exec app php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\JWTAuthServiceProvider"
docker-compose exec app php artisan jwt:generate
docker-compose exec app composer require ramsey/uuid
docker-compose exec app composer  require "styde/html=~1.2"
docker-compose exec app composer require chumper/zipper
docker-compose exec database mysql -u root -e "CREATE DATABASE IF NOT EXISTS homestead"
docker-compose exec app php artisan migrate
docker-compose exec app php artisan migrate:refresh
docker-compose exec app php artisan db:seed
docker-compose exec app chmod 777 -R storage
docker-compose exec app chmod 777 -R public/images
docker-compose exec app chmod 777 -R public/unzippedCorpus
docker-compose exec app mkdir public/tempZippedCorpus
docker-compose exec app chmod 777 -R public/tempZippedCorpus