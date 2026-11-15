#!/bin/sh
set -e

# Render indica el puerto en la variable PORT
PORT="${PORT:-10000}"
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Enlace para que las imágenes subidas sean visibles
php artisan storage:link || true

# Cachés de configuración y vistas
php artisan config:cache
php artisan view:cache

# Crea o actualiza las tablas (si ya existen no hace nada)
php artisan migrate --force

# Los archivos creados arriba deben ser escribibles por Apache
chown -R www-data:www-data storage bootstrap/cache

exec apache2-foreground
