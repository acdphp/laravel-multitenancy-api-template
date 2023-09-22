# Laravel API Template

[![laravel](https://img.shields.io/badge/Laravel-10.23-blue)](https://laravel-news.com/laravel-10-23-0)

- Built for headless application, removed all view related files.
- Using passport instead of sanctum.
- Base user authentication class.
- Base company class for multi-tenancy.

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
