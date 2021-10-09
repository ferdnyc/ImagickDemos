#!/usr/bin/env bash


docker-compose build
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up  --build --force-recreate installer
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up  --build --force-recreate js_builder

docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build --force-recreate imagick_php_backend web_server

# redis
# workers
