# Laravel API Template

[![laravel](https://img.shields.io/badge/Laravel-10.10-blue)](https://laravel-news.com/laravel-10-10-0)

- Built for headless application, removed all view related files.
- Using passport instead of sanctum.
- Base user authentication class.
- Base company class for multi-tenancy.

## Local docker-compose deployment
1. Copy `.env.example` to `.env`
    ```sh
    cp .env.example .env
    ```

2. Copy git pre-push linter script hook
    ```sh
    cp -rp .scripts/git/pre-push.sh .git/hooks/pre-push
    ```

3. Install dependencies
    ```sh
    composer i --optimize-autoloader --no-interaction
    ```

4. Run containers
    ```sh
    ./vendor/bin/sail up --build -d
    ```
   
5. Generate IDE helpers
   ```sh
   ./vendor/bin/sail artisan ide-helper:generate
   ./vendor/bin/sail artisan ide-helper:meta
   ```

6. Generate Passport keys
   ```sh
   ./vendor/bin/sail artisan passport:keys
   ```

7. Run database migration and seeder
   ```sh
   ./vendor/bin/sail artisan migrate:fresh --seed
   ```

8. Seed dummy data (optional)
    ```sh
    ./vendor/bin/sail artisan db:seed --class=TestDatabaseSeeder
    ```

9. Health Check
    ```sh
    curl http://localhost/api/health-check
    
    # Output: {"status":"ok"}
    ```
