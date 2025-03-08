FROM ubuntu:22.04

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && \
    apt-get install -y software-properties-common

RUN add-apt-repository ppa:purplei2p/i2pd && \
    apt-get update

RUN apt-get install -y i2pd

ADD ./tunnels.conf  /etc/i2pd/tunnels.conf
ADD ./i2pd.conf     /etc/i2pd/i2pd.conf
ADD ./init.sh       /init.sh

CMD [ "bash", "/init.sh" ]
