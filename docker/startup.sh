#!/bin/sh

#This function runs all cronjob
runBackgroundJobs() {
    sleep $WAIT_FOR
    php "/app/artisan release:check"
}

#Change the listen port
sed -i "s,LISTEN_PORT,$PORT,g" /etc/nginx/nginx.conf

#start Services
php-fpm -D

#wait until php-fpm has started
while ! nc -w 1 -z 127.0.0.1 9000; do sleep 0.1; done

#run background jobs
runBackgroundJobs &

#run nginx in the background
nginx

##Listen to all system messages
#tail -f /var/log/messages
