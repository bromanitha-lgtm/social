# Article

Article is a community platform for publishing posts and connecting writers with readers. Members can create articles, follow writers, and engage through likes and comments.

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- SQLite or MySQL

## Setup

1. Install PHP dependencies:

   ```powershell
   composer install
   ```

2. Create your local environment file and application key:

   ```powershell
   Copy-Item .env.example .env
   php artisan key:generate
   ```

3. Configure the database in `.env`, then run migrations:

   ```powershell
   php artisan migrate
   ```

4. Install frontend dependencies and build the assets:

   ```powershell
   npm install
   npm run build
   ```

5. Start the application:

   ```powershell
   php artisan serve
   ```

## Development

Run the development processes with:

```powershell
composer run dev
```

Run the test suite with:

```powershell
php artisan test
```
