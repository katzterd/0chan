#!/usr/bin/env bash

mkdir -p /var/lib/tor/hidden_service

if [ -z "${TORGATE_HOSTNAME}" ]; then
    echo "TORGATE_HOSTNAME environment var is undefined, torgate will be disabled";
    exit 0;
else
    echo "${TORGATE_HOSTNAME}" > /var/lib/tor/hidden_service/hostname
fi

if [ -z "${TORGATE_PRIVATE_KEY}" ]; then
    echo "TORGATE_PRIVATE_KEY environment var is undefined, torgate will be disabled";
    exit 0;
else
    echo "${TORGATE_PRIVATE_KEY}" | base64 -d >> /var/lib/tor/hidden_service/hs_ed25519_secret_key
fi

if [ -z "${TORGATE_PUBLIC_KEY}" ]; then
    echo "TORGATE_PUBLIC_KEY environment var is undefined, torgate will be disabled";
    exit 0;
else
    echo "${TORGATE_PUBLIC_KEY}" | base64 -d >> /var/lib/tor/hidden_service/hs_ed25519_public_key
fi

if [ -z "${GATE_ENDPOINT_HOST}" ]; then
    echo "GATE_ENDPOINT_HOST environment var is undefined, torgate will be disabled";
    exit 0;
else
    sed -i 's/\__GATE_ENDPOINT_HOST__/'"${GATE_ENDPOINT_HOST}"'/' /etc/tor/torrc
fi

if [ -z "${GATE_ENDPOINT_PORT}" ]; then
    echo "GATE_ENDPOINT_PORT environment var is undefined, torgate will be disabled";
    exit 0;
else
    sed -i 's/\__GATE_ENDPOINT_PORT__/'"${GATE_ENDPOINT_PORT}"'/' /etc/tor/torrc
fi

chmod -R 600 /var/lib/tor/hidden_service
echo "Torgate started: ${TORGATE_HOSTNAME} -> ${GATE_ENDPOINT_HOST}:${GATE_ENDPOINT_PORT}"

tor
