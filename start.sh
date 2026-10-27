#!/bin/bash

/usr/bin/mariadbd-safe &

while ! mariadb-admin ping >/dev/null 2>&1; do sleep 1; done

echo "grant all privileges on ${MYSQL_DATABASE}.* to '${MYSQL_USER}'@'%' identified by '${MYSQL_PASSWORD}' with grant option; grant all privileges on *.* to 'root'@'%' identified by '${MYSQL_ROOT_PASSWORD}' with grant option ; flush privileges; " |  mariadb

cat
