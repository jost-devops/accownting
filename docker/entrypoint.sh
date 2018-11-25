#!/bin/sh

echo "$(date) Waiting for MySQL service ..."

wait-for-it -t 60 -h db -p 3306 -s -- php /var/www/bin/console doctrine:migrations:migrate --allow-no-migration --no-interaction

echo "$(date) Doctrine Migrations executed ..."

# touch cron stuff due to debian stretch security policy --> https://unix.stackexchange.com/questions/453006/getting-cron-to-work-on-docker
touch /etc/crontab /etc/cron.*/*

# start cron scheduler (in background automatically)
service cron start

apache2-foreground
