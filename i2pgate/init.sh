#!/usr/bin/env bash

if [ -z "${I2PGATE_PRIVATE_KEY}" ]; then
    echo "I2PGATE_PRIVATE_KEY environment var is undefined, i2pgate will be disabled";
    exit 0;
else
    echo "${I2PGATE_PRIVATE_KEY}" | base64 -d >> /var/lib/i2pd/secret_key.dat
fi

if [ -z "${GATE_ENDPOINT_HOST}" ]; then
    echo "GATE_ENDPOINT_HOST environment var is undefined, i2pgate will be disabled";
    exit 0;
else
    sed -i 's/\__GATE_ENDPOINT_HOST__/'"${GATE_ENDPOINT_HOST}"'/g' /etc/i2pd/tunnels.conf
fi

if [ -z "${GATE_ENDPOINT_PORT}" ]; then
    echo "GATE_ENDPOINT_PORT environment var is undefined, i2pgate will be disabled";
    exit 0;
else
    sed -i 's/\__GATE_ENDPOINT_PORT__/'"${GATE_ENDPOINT_PORT}"'/' /etc/i2pd/tunnels.conf
fi

printf "i2pgate started: ${GATE_ENDPOINT_HOST}:${GATE_ENDPOINT_PORT}\n\n"

i2pd --service --conf /etc/i2pd/i2pd.conf
