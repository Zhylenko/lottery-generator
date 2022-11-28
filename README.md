# Lottery Generator

### Clone repo
```
git clone git@github.com:Zhylenko/lottery-generator.git
```

### Download packages
```
composer update
```

### Test
Change data  in **.env.testing** file.
```
php artisan key:generate --env=testing
php artisan optimize:clear --env=testing
php artisan test
```

### Set up **.env**
```
cp .env.example .env
```
Change data  in **.env** file.
```
php artisan key:generate
php artisan migrate --seed
php artisan optimize:clear
```