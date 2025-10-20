#!/usr/bin/env ash

if [ -z "${TORGATE_HOSTNAME}" ]; then
    echo "TORGATE_HOSTNAME environment var is undefined, onion header will be disabled";
    sed -i 's/__TORGATE_HEADER__//' /app/deploy/nginx.conf
else
    sed -i 's/__TORGATE_HEADER__/'"add_header Onion-Location http:\/\/${TORGATE_HOSTNAME}\$request_uri;"'/' /app/deploy/nginx.conf
fi

supervisord -c /app/deploy/supervisord.conf
