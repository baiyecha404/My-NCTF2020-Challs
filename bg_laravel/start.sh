#!/bin/sh

chmod 600 /start.sh

# set up mysql
mkdir -p /run/mysqld
chown -R mysql:mysql /run/mysqld
mysql_install_db --user=mysql --ldata=/var/lib/mysql
mysqld --user=mysql --console --skip-name-resolve --skip-networking=0 &
while ! mysqladmin ping -h'localhost' --silent; do echo "not up" && sleep .2; done

mysql -u root << EOF
CREATE DATABASE taichi;

CREATE USER 'baoguo'@'127.0.0.1' IDENTIFIED BY 'haoziweizhi';
GRANT ALL ON taichi.* TO 'baoguo'@'127.0.0.1';
ALTER USER 'root'@'localhost' IDENTIFIED BY '';
FLUSH PRIVILEGES;

EOF

# laravel database migrate
php artisan config:clear
php artisan migrate

# Cronjob to delete files every 30 minutes
echo '*/30 * * * * rm /app/public/uploads/*' >> /var/spool/cron/crontabs/root

# Start cron deamon
crond -f &

# Start supervisord services
/usr/bin/supervisord -c /etc/supervisord.conf

