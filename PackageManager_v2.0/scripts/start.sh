#!/bin/sh
  
/home/node/restart.sh > /dev/null 2>&1 &
/usr/bin/supervisord -c /etc/supervisord.conf 
