#!/bin/bash

function error { echo -e "\033[31mError: $1" >&2; }
function version { echo "$@" | awk -F. '{ printf("%d%03d%03d%03d\n", $1,$2,$3,$4); }'; }

# Check .env
if [ -f .env ]
then
    export "$(<.env sed 's/#.*//g' | xargs)"
else
    error '.env not found'
    exit 1
fi

# Check docker-compose must be version 2.1 or higher
DOCKER_COMPOSE_MIN_VER=2.1
DOCKER_COMPOSE_VER=$(docker-compose --version | cut -d'v' -f3)
if [ "$(version "$DOCKER_COMPOSE_VER")" -lt "$(version "$DOCKER_COMPOSE_MIN_VER")" ]; then
    echo error "docker-compose version must be $DOCKER_COMPOSE_MIN_VER or higher; you have $DOCKER_COMPOSE_VER"
    exit 1
fi

# Copy pre-push hook script
if command -v cp &> /dev/null
then
    cp -rp ./.scripts/git/pre-push.sh .git/hooks/pre-push
fi

# Build Laravel
docker-compose up -d --build --wait --remove-orphans && \
    docker-compose exec api composer install --optimize-autoloader --no-interaction && \
    docker-compose exec api php artisan ide-helper:generate && \
    docker-compose exec api php artisan ide-helper:meta && \
    docker-compose exec api php artisan key:generate --ansi && \
    docker-compose exec api php artisan passport:keys -q -n 2>/dev/null || true && \
    docker-compose exec api php artisan migrate && \
    docker-compose exec api php artisan db:seed

# Create s3 public bucket
docker run --rm --link s3:minio --network app --entrypoint sh minio/mc -c "\
  mc config host add s3 http://s3:9000 ${AWS_ACCESS_KEY_ID} ${AWS_SECRET_ACCESS_KEY}; \
  mc mb -p s3/${AWS_BUCKET}; \
  mc policy set public s3/${AWS_BUCKET}; \
  exit 0; \
"
