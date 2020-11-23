FROM lorisleiva/laravel-docker

RUN adduser -D -u 1000 -g 1000 -s /bin/sh www

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories &&\
    apk update &&\ 
    apk add mariadb mariadb-client supervisor nginx php-fpm curl build-base php7-json php7-fileinfo php7-phar php7-session  openssl-dev php7-dev php7-openssl php7-tokenizer php7-pdo php7-pdo_mysql &&\
    mkdir -p /app 

WORKDIR /app

COPY web .

COPY config/fpm.conf /etc/php7/php-fpm.d/www.conf
COPY config/supervisord.conf /etc/supervisord.conf
COPY config/nginx.conf /etc/nginx/nginx.conf

COPY flag /root/flag

COPY readflag.c /

RUN gcc -o /readflag /readflag.c && chmod 4755 /readflag && rm /readflag.c

RUN chown -R www:www /app/public/uploads /var/lib/nginx

EXPOSE 80

COPY --chown=root start.sh /start.sh

RUN chmod +x /start.sh && chown -R www:www /app/storage

ENTRYPOINT ["/start.sh"]

