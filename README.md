## Installation
```
$ git clone https://github.com/mxsgx/si-ajudana-umy.git
$ cd si-ajudana-umy
$ composer update
$ cp .env.example .env
$ php artisan key:generate

// Configure your database connection

$ php artisan migrate --seed
$ php artisan serve

// Now server listen at http://localhost:8000
```
