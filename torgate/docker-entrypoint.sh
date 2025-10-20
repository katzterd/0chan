#!/usr/bin/env ash

mkdir -p /var/lib/tor/hidden_service

if [ -z "${LOCALGW_KEY}" ]; then
    echo "LOCALGW_KEY environment var is undefined, torgate will be disabled";
    exit 0;
else
    sed -i 's/__LOCALGW_KEY__/'"${LOCALGW_KEY}"'/' /nginx.conf
fi

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
    echo "${TORGATE_PRIVATE_KEY}" | base64 -d > /var/lib/tor/hidden_service/hs_ed25519_secret_key
fi

if [ -z "${TORGATE_PUBLIC_KEY}" ]; then
    echo "TORGATE_PUBLIC_KEY environment var is undefined, torgate will be disabled";
    exit 0;
else
    echo "${TORGATE_PUBLIC_KEY}" | base64 -d > /var/lib/tor/hidden_service/hs_ed25519_public_key
fi

chmod -R 600 /var/lib/tor/hidden_service
echo "Torgate started: ${TORGATE_HOSTNAME} -> frontend:80"

supervisord -c ./supervisord.conf
