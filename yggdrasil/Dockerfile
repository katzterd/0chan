FROM ubuntu:22.04

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && \
    apt-get install -y  supervisor \
                        unzip \
                        socat \
                        curl \
                        gpg

RUN mkdir -p /usr/local/apt-keys && \
    gpg --fetch-keys https://neilalexander.s3.dualstack.eu-west-2.amazonaws.com/deb/key.txt && \
    gpg --export BC1BF63BD10B8F1A | tee /usr/local/apt-keys/yggdrasil-keyring.gpg > /dev/null

RUN echo 'deb [signed-by=/usr/local/apt-keys/yggdrasil-keyring.gpg] http://neilalexander.s3.dualstack.eu-west-2.amazonaws.com/deb/ debian yggdrasil' | tee /etc/apt/sources.list.d/yggdrasil.list && \
    apt-get update

RUN mkdir -p /var/run/yggdrasil

RUN apt-get install -y yggdrasil

WORKDIR /ygg

RUN curl -L -o ./peers_updater.zip https://github.com/ygguser/peers_updater/releases/download/0.3.4/x86_64-unknown-linux-gnu.zip
RUN unzip ./peers_updater.zip && rm ./peers_updater.zip

ADD ./docker-entrypoint.sh    /docker-entrypoint.sh
ADD ./supervisord.conf        ./supervisord.conf
ADD ./yggdrasil.conf          ./yggdrasil.conf

ENTRYPOINT [ "bash", "/docker-entrypoint.sh" ]
