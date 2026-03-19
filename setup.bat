cd desafio-front-end
call npm install
cd ..
cd desafio-back-end
call composer install --no-interaction
php artisan migrate --force
php artisan storage:link