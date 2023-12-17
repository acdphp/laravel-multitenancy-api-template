# Laravel Multi-tenancy API Template

[![laravel](https://img.shields.io/badge/Laravel-10.26-blue)](https://laravel-news.com/laravel-10-26-0)

- Base company class for multi-tenancy using [acdphp/laravel-multitenancy](https://packagist.org/packages/acdphp/laravel-multitenancy).
- Base user authentication class.

## Requirements
- [Docker compose v2.1 or higher](https://docs.docker.com/compose/)

## Local docker-compose deployment
1. Copy `.env.example` to `.env`
    ```sh
    cp .env.example .env
    ```

2. Start
    ```sh
    ./start_local.sh
    ```
   
3. Create oauth password grant client
    ```sh
    docker-compose exec -it api php artisan passport:client --password
    ```

4. Seed dummy data (optional)
    ```sh
    docker-compose exec -it api php artisan db:seed --class=TestDatabaseSeeder
    ```

5. Health Check
    ```sh
    curl http://localhost/api/health-check
    
    # Output: {"status":"ok"}
    ```

6. Stop
    ```sh
    docker-compose down
    ```
