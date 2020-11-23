#!/bin/bash

chmod 700 /start.sh
chmod +x /root/clean.sh
/root/clean.sh > /dev/null 2>&1 &
/usr/sbin/apache2ctl -D FOREGROUND
