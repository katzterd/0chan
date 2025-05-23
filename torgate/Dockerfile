FROM ubuntu:22.04

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && \
    apt-get install -y  supervisor \
                        socat \
                        nginx \ 
                        tor \
                        curl

ADD ./docker-entrypoint.sh  /docker-entrypoint.sh
ADD ./nginx.conf            /nginx.conf
ADD ./torrc.conf            /etc/tor/torrc
ADD ./supervisord.conf      /supervisord.conf

CMD [ "bash", "/docker-entrypoint.sh" ]
