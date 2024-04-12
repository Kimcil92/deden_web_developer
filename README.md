# Web Developer Test

[Deden Setiawan](https://www.instagram.com/deden_setiawann/)

## Authors

-   [@Kimcil92](https://github.com/Kimcil92) (Deden Setiawan)

## Deployment

To deploy this project run

-   copy .env and modify it

```bash
cp .env.example .env
```

-   install depedency

```bash
composer install
```

-   generate key

```bash
php artisan key:generate
```

-   create the symbolic link

```bash
php artisan storage:link
```

-   migrate database

```bash
php artisan migrate --seed
```

-   schedule

```bash
php artisan schedule:run
```

-   run

```bash
php artisan serve
```
