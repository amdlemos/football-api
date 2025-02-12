#!/usr/bin/env bash

if [ "$SUPERVISOR_PHP_USER" != "root" ] && [ "$SUPERVISOR_PHP_USER" != "sail" ]; then
    echo "You should set SUPERVISOR_PHP_USER to either 'sail' or 'root'."
    exit 1
fi

if [ ! -z "$WWWUSER" ]; then
    usermod -u $WWWUSER sail
fi

if [ ! -d /.composer ]; then
    mkdir /.composer
fi

chmod -R ugo+rw /.composer

# Variáveis para conectar ao banco
# DB_HOST="$DB_HOST:-mysql}" # ou use o nome do serviço no Docker
# DB_PORT="${DB_PORT:-3306}"
# DB_USER="${DB_USERNAME:-sail}"
# DB_PASS="${DB_PASSWORD:-password}"

# Aguarda o banco de dados estar acessível
echo "Waiting for MySQL to be available..."
until mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USER" -p"$DB_PASSWORD" -e "SELECT 1" > /dev/null 2>&1; do
    echo "MySQL is not ready yet. Waiting..."
    sleep 5
done

echo "MySQL is ready. Running database migrations..."
php artisan migrate --force

if [ $# -gt 0 ]; then
    if [ "$SUPERVISOR_PHP_USER" = "root" ]; then
        exec "$@"
    else
        exec gosu $WWWUSER "$@"
    fi
else
    exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
fi
