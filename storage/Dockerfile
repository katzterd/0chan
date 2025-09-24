FROM golang:1.25.1-alpine AS builder

WORKDIR /app

RUN apk add --no-cache git

COPY /src/go.mod /src/go.sum ./

RUN go mod download

COPY /src .

RUN CGO_ENABLED=0 GOOS=linux go build -a -installsuffix cgo -o storage cmd/storage/main.go


FROM alpine:latest

RUN apk --no-cache add tzdata nginx supervisor ffmpeg

WORKDIR /app

ADD ./config  ./config
ADD ./docker-entrypoint.sh ./docker-entrypoint.sh

COPY --from=builder /app/storage .

EXPOSE 80
EXPOSE 228
EXPOSE 53

CMD [ "ash", "/app/docker-entrypoint.sh" ]