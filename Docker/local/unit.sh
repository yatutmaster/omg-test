#!/bin/sh

sleep 3

/usr/bin/curl -s -X GET --unix-socket /var/run/control.unit.sock http://localhost/
/usr/bin/curl -s -X PUT --data-binary @/unit-conf.json --unix-socket /var/run/control.unit.sock http://localhost/config/
