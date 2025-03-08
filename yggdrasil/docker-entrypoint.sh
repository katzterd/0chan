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

    if [ -z "${GATE_ENDPOINT_HOST}" ]; then
        echo "GATE_ENDPOINT_HOST environment var is undefined, yggdrasil will be disabled";
        exit 0;
    else
        sed -i 's/\__GATE_ENDPOINT_HOST__/'"${GATE_ENDPOINT_HOST}"'/g' ./supervisord.conf
    fi
    
    if [ -z "${GATE_ENDPOINT_PORT}" ]; then
        echo "GATE_ENDPOINT_PORT environment var is undefined, yggdrasil will be disabled";
        exit 0;
    else
        sed -i 's/\__GATE_ENDPOINT_PORT__/'"${GATE_ENDPOINT_PORT}"'/g' ./supervisord.conf
    fi
    
printf "Updating peers...\n\n"
./peers_updater -c ./yggdrasil.conf -n 5 -u

printf "yggdrasil started: ${GATE_ENDPOINT_HOST}:${GATE_ENDPOINT_PORT}\n\n"
supervisord -c ./supervisord.conf

fi
