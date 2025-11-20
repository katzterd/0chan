#!/usr/bin/env ash
chown -R 33:33 /storage
exec supervisord -c /app/deploy/supervisord.conf
