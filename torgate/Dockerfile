FROM alpine:3.22.1

RUN apk --no-cache add  supervisor \
                        nginx \ 
                        socat \
                        curl \
                        tor


ADD ./docker-entrypoint.sh  /docker-entrypoint.sh
ADD ./nginx.conf            /nginx.conf
ADD ./torrc.conf            /etc/tor/torrc
ADD ./supervisord.conf      /supervisord.conf

CMD [ "ash", "/docker-entrypoint.sh" ]
