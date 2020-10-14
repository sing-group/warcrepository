FROM debian:9

#Moncho: quitar libmcrypt-dev php-pear php7.0-dev libmagickwand-dev
RUN apt-get update && apt-get install -y php7.0-fpm \
    php7.0-mbstring php7.0-xml zip unzip php7.0-zip php-imagick mysql-client \
    php7.0-mcrypt php7.0-mysql php-cli --no-install-recommends && \
    mkdir /run/php && \
    apt-get -y install composer && \
    apt-get -y install default-jdk && \
    sed -i.bak "s/^listen =.*$/listen = 0.0.0.0:9000/g" \
    /etc/php/7.0/fpm/pool.d/www.conf && \
    sed -i.bak -e "s/^upload_max_filesize.*$/upload_max_filesize = 0/g" \
    -e "s/^post_max_size.*$/post_max_size = 0/g" \
    /etc/php/7.0/fpm/php.ini

ENTRYPOINT php-fpm7.0 -F -c /etc/php/7.0/fpm

EXPOSE 9000
