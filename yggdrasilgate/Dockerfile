FROM alpine:3.22.1

RUN apk --no-cache --no-scripts add yggdrasil

RUN apk --no-cache add  supervisor \
                        nginx \
                        socat

WORKDIR /ygg

ADD ./docker-entrypoint.sh    /docker-entrypoint.sh
ADD ./nginx.conf              /nginx.conf
ADD ./supervisord.conf        /supervisord.conf
ADD ./yggdrasil.conf          /yggdrasil.conf
ADD ./peers_updater           /peers_updater

ENTRYPOINT [ "ash", "/docker-entrypoint.sh" ]
