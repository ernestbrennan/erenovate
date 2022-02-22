#!/bin/bash

cp .env.example .env
cd docker
docker stop $(docker ps -sq)
docker-compose up -d nginx mysql workspace
docker-compose exec workspace  bash -c "cd erenovate_guarantee && composer install"
docker-compose exec workspace  bash -c "cd erenovate_guarantee && yarn install"
docker-compose exec workspace  bash -c "cd erenovate_guarantee && php artisan migrate"
docker-compose exec workspace  bash -c "cd erenovate_guarantee && yarn run dev"