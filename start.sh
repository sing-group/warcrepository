#!/bin/bash

/usr/bin/mysqld_safe &

sleep 10 

echo "grant all privileges on ${MYSQL_DATABASE}.* to '${MYSQL_USER}'@'%' identified by '${MYSQL_PASSWORD}' with grant option; grant all privileges on *.* to 'root'@'%' identified by '${MYSQL_ROOT_PASSWORD}' with grant option ; flush privileges; " | mysql 

tail -f /var/log/mysql/* 

