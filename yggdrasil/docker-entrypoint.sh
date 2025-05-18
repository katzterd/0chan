#!/usr/bin/env bash

for ARGS in $@; do
	    case $ARGS in    
	        "getaddr")     echo "Your yggdrasil address is: http://[$(yggdrasilctl getSelf | grep "IPv6 address" | cut -d ' ' -f '8' | xargs)]";;
	    esac
  done


if [[ -z $@ ]]; then

    if [ -z "${YGGDRASILGATE_PRIVATE_KEY}" ]; then
        echo "YGGDRASILGATE_PRIVATE_KEY environment var is undefined, yggdrasil will be disabled";
        exit 0;
    else
        sed -i 's/__YGGDRASILGATE_PRIVATE_KEY__/'"PrivateKey: ${YGGDRASILGATE_PRIVATE_KEY}"'/' ./yggdrasil.conf
    fi
    
printf "Updating peers...\n\n"
./peers_updater -c ./yggdrasil.conf -n 5 -u

printf "yggdrasilgate started: frontend:80\n\n"

supervisord -c ./supervisord.conf

fi
