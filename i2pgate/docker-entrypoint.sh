#!/usr/bin/env ash

if [ -z "${LOCALGW_KEY}" ]; then
    echo "LOCALGW_KEY environment var is undefined, i2pgate will be disabled";
    exit 0;
else
    sed -i 's/__LOCALGW_KEY__/'"${LOCALGW_KEY}"'/' /nginx.conf
fi

if [ -z "${I2PGATE_PRIVATE_KEY}" ]; then
    echo "I2PGATE_PRIVATE_KEY environment var is undefined, i2pgate will be disabled";
    exit 0;
else
    echo "${I2PGATE_PRIVATE_KEY}" | base64 -d > /var/lib/i2pd/secret_key.dat
fi

printf "i2pgate started\n\n"

exec supervisord -c ./supervisord.conf
