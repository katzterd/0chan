FROM ubuntu:22.04

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y supervisor socat tor curl

ADD ./docker-entrypoint.sh  /docker-entrypoint.sh
ADD ./supervisord.conf      /supervisord.conf
ADD ./torrc.conf            /etc/tor/torrc

CMD [ "bash", "/docker-entrypoint.sh" ]
