FROM debian:9

RUN apt-get update && apt-get install -y nginx-light \
    --no-install-recommends 

ADD vhost.conf /etc/nginx/sites-available/default
ADD nginx.conf /etc/nginx/nginx.conf

ENTRYPOINT nginx ; tail -f /var/log/nginx/*
