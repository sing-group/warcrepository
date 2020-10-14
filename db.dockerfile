FROM debian:9

ADD start.sh /root/start.sh

RUN apt-get update && apt-get install -y mysql-server \
    --no-install-recommends && \
    sed -i.bak "s/127\.0\.0\.1/0.0.0.0/g" \
    /etc/mysql/mariadb.conf.d/50-server.cnf && \
    chmod 755 /root/start.sh

ENV MYSQL_DATABASE=homestead
ENV MYSQL_USER=homestead
ENV MYSQL_PASSWORD=secret
ENV MYSQL_ROOT_PASSWORD=secret

EXPOSE 3306

ENTRYPOINT /root/start.sh
