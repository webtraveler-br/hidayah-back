web: vendor/bin/heroku-php-apache2 public/
release: php artisan migrate:fresh --seed --force && rm public/storage && php artisan storage:link

