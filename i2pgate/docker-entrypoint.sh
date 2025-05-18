#!/usr/bin/env bash

if [ -z "${I2PGATE_PRIVATE_KEY}" ]; then
    echo "I2PGATE_PRIVATE_KEY environment var is undefined, i2pgate will be disabled";
    exit 0;
else
    echo "${I2PGATE_PRIVATE_KEY}" | base64 -d > /var/lib/i2pd/secret_key.dat
fi

printf "i2pgate started\n\n"

supervisord -c /supervisord.conf
