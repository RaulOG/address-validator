# Installation
* Install composer dependencies
```
docker run --rm \
   -u "$(id -u):$(id -g)" \
   -v "$(pwd):/var/www/html" \
   -w /var/www/html \
   laravelsail/php83-composer:latest \
   composer install --ignore-platform-reqs
```
* Start sailing
```
./vendor/bin/sail up -d
```
* Create environmental file
```
cp .env.example .env
```
* Generate application key
```
./vendor/bin/sail artisan key:generate
```
* Create sqlite database file
```
touch database/database.sqlite
```
* Run migrations
```
./vendor/bin/sail artisan migrate
```
* Install npm dependencies
```
./vendor/bin/sail npm install
```
* Compile assets
```
./vendor/bin/sail npm run build
```
* Add GEOAPIFY_API_KEY in `.env`
