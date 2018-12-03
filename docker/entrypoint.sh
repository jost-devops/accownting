#!/bin/sh

echo "$(date) Waiting for MySQL service ..."

wait-for-it -t 60 -h db -p 3306 -s -- php /var/www/bin/console doctrine:migrations:migrate --allow-no-migration --no-interaction

echo "$(date) Doctrine Migrations executed ..."

apache2-foreground
