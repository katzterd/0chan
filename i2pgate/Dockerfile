FROM alpine:3.22.1

RUN apk --no-cache add  supervisor \
                        nginx \
                        socat \
                        curl \
                        i2pd

ADD ./docker-entrypoint.sh      /docker-entrypoint.sh
ADD ./nginx.conf                /nginx.conf
ADD ./tunnels.conf              /etc/i2pd/tunnels.conf
ADD ./i2pd.conf                 /etc/i2pd/i2pd.conf
ADD ./supervisord.conf          /supervisord.conf

CMD [ "ash", "/docker-entrypoint.sh" ]
