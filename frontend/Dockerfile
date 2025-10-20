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


FROM alpine:3.22.1

RUN apk --no-cache add supervisor \
                       nginx \
                       socat

WORKDIR /app

ADD ./deploy    ./deploy

COPY --from=builder /builder/dist ./dist

EXPOSE 80

CMD [ "ash", "/app/deploy/docker-entrypoint.sh" ]
