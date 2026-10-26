#!/bin/sh
# Démarrage du conteneur : configuration, base de données, puis serveur web.
set -e

php artisan config:cache
php artisan view:cache

php artisan migrate --force
php artisan db:seed --force

exec apache2-foreground
