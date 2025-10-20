#!/usr/bin/env ash
chown -R 33:33 /storage
supervisord -c /app/deploy/supervisord.conf
