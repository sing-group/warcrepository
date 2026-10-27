FROM debian:13

RUN apt-get update && apt-get install -y \
      php-fpm \
      php-mbstring \
      php-xml \
      zip \
      unzip \
      php-zip \
      php-imagick \
      mariadb-client \
      php-mcrypt \
      php-mysql \
      php-cli \
      php-mysql \
      php-mysqli \
      php-curl \
      php-gd \
      php-mbstring \
      php-xml \
      php-xmlrpc \
      php-soap \
      php-intl \
      composer \
      default-jdk \
      cron \
      nodejs \
      npm \
      git \
    --no-install-recommends && \
    sed -i.bak "s/^listen =.*$/listen = 0.0.0.0:9000/g" \
       /etc/php/8.4/fpm/pool.d/www.conf && \
    sed -i.bak -e "s/^upload_max_filesize.*$/upload_max_filesize = 0/g" \
               -e "s/^post_max_size.*$/post_max_size = 0/g" \
               /etc/php/8.4/fpm/php.ini && \
    useradd -b /home -d /home/moncho -s /bin/bash -g  users -k /etc/skel --create-home moncho

ENTRYPOINT bash /var/www/afterstart.sh ; php-fpm8.4 -F -c /etc/php/8.4/fpm

EXPOSE 9000

