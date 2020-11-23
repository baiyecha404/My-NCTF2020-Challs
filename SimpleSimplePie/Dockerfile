FROM php:7.0-apache

# Change apt source for chinese area users
COPY sources.list  /etc/apt/sources.list 

# Fix docker-php-ext-install bug
RUN sed -i "s/docker-php-\(ext-$ext.ini\)/\1/" /usr/local/bin/docker-php-ext-install

# Install memcache extension
RUN set -x \
    && apt-get update && apt-get install -y --no-install-recommends unzip libssl-dev libpcre3 libpcre3-dev zlib1g-dev vim\
    && cd /tmp \
    && curl -sSL -o php7.zip https://github.com/websupport-sk/pecl-memcache/archive/php7.zip \
    && unzip php7 \
    && cd pecl-memcache-php7 \
    && /usr/local/bin/phpize \
    && ./configure --with-php-config=/usr/local/bin/php-config \
    && make \
    && make install \
    && echo "extension=memcache.so" > /usr/local/etc/php/conf.d/ext-memcache.ini \
    && rm -rf /tmp/pecl-memcache-php7 php7.zip

# Change working directory
RUN sed -i "s/\/var\/www\/html/\/var\/www\/html\/public/g" /etc/apache2/sites-enabled/000-default.conf
# Copy sourcecode
COPY simplepie /var/www/html

# Add readflag binary
COPY readflag.c /

RUN gcc -o /readflag /readflag.c && chmod 4755 /readflag && rm /readflag.c

# Copy flag
COPY flag /root/flag

# Set permission to log directory
RUN chmod 777 /var/www/html/public/cache_logs && chmod 777 /var/www/html/public/cache

# Set entrypoint
COPY --chown=root start.sh /start.sh

# Copy cleanup script
COPY --chown=root clean.sh /root/clean.sh
# Set Permission 
RUN chmod +x /start.sh

RUN chmod +x /root/clean.sh

ENTRYPOINT ["/start.sh"]
 
