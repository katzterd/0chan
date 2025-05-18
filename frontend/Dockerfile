FROM node:fermium AS install

WORKDIR /install

ADD package.json package-lock.json .

RUN npm install


FROM node:fermium AS builder

WORKDIR /builder

COPY --from=install /install/node_modules ./node_modules

ADD package.json package-lock.json .
ADD ./build                        ./build
ADD ./config                       ./config
ADD ./.babelrc                     ./.babelrc
ADD ./index.html                   ./index.html
ADD ./static                       ./static
ADD ./src                          ./src

RUN npm run build


FROM ubuntu:22.04

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update -y && \
    apt-get install -y nginx nginx-extras supervisor socat

ADD ./deploy/docker-entrypoint.sh   /docker-entrypoint.sh
ADD ./deploy/supervisord.conf       /supervisord.conf
ADD ./deploy/nginx.conf             /nginx.conf

COPY --from=builder /builder/dist /dist

EXPOSE 80

CMD [ "bash", "/docker-entrypoint.sh" ]
