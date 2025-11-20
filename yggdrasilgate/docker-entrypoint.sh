#!/usr/bin/env ash

for ARGS in $@; do
	    case $ARGS in    
	        "getaddr")     echo "Your yggdrasil address is: http://[$(yggdrasilctl getSelf | grep "IPv6 address" | cut -d ' ' -f '8' | xargs)]";;
	    esac
  done


if [[ -z $@ ]]; then

    if [ -z "${LOCALGW_KEY}" ]; then
        echo "LOCALGW_KEY environment var is undefined, yggdrasilgate will be disabled";
        exit 0;
    else
        sed -i 's/__LOCALGW_KEY__/'"${LOCALGW_KEY}"'/' /nginx.conf
    fi

    if [ -z "${YGGDRASILGATE_PRIVATE_KEY}" ]; then
        echo "YGGDRASILGATE_PRIVATE_KEY environment var is undefined, yggdrasilgate will be disabled";
        exit 0;
    else
        sed -i 's/__YGGDRASILGATE_PRIVATE_KEY__/'"PrivateKey: ${YGGDRASILGATE_PRIVATE_KEY}"'/' /yggdrasil.conf
    fi
    
printf "Updating peers...\n\n"
/peers_updater -c /yggdrasil.conf -n 5 -u

printf "yggdrasilgate started\n\n"

exec supervisord -c /supervisord.conf

fi
