#!/bin/bash
while true
  do
         find /var/www/html/public/cache_logs/ -type f  -delete
         echo flush_all > /dev/tcp/172.22.0.4/11211
         sleep 300
  done
