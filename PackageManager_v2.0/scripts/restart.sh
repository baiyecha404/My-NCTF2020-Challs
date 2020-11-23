#!/bin/sh
while true
do
         kill -9 $( ps -ef | grep  'node /app/app.js' |grep -v grep | awk '{print $1}')
         find /tmp -type f  -regex  ".*\.js" -delete
	     sleep 600
done

