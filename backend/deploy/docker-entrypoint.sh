#!/usr/bin/env bash
  for ARGS in $@; do
	    case $ARGS in    
	        "createdb")     cd /src/meta; bash createdb.sh;;
	        "createadmin")  cd /src/meta; bash createadmin.sh;;
	    esac
  done
  
  if [[ -z $@ ]]; then
        cd /src/console/
        while true; do 
        exec php daemon.php
        sleep 60
        done & \
        exec php-fpm7.4 -O -F --fpm-config /src/deploy/php-fpm.conf 
  fi
