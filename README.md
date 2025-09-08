```
 ██████╗  ██████╗██╗  ██╗██╗  ██╗ ██████╗ 
██╔═████╗██╔════╝██║  ██║██║ ██╔╝██╔═══██╗
██║██╔██║██║     ███████║█████╔╝ ██║   ██║
████╔╝██║██║     ██╔══██║██╔═██╗ ██║   ██║
╚██████╔╝╚██████╗██║  ██║██║  ██╗╚██████╔╝
 ╚═════╝  ╚═════╝╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝  
```
![CI](https://img.shields.io/github/actions/workflow/status/katzterd/0chan/docker-build.yml?label=CI&logo=github&style=for-the-badge)

## Installation

### Docker compose way

#### 1. Prepare .env 
```console
cp .env-dist .env
```
Then fill fields in `.env` by your text editor with needed values

#### 2. Deploy
```console
docker compose up -d
```

#### 3. Setup db and admin account
```console
docker exec -t backend /src/config/docker-entrypoint.sh createdb createadmin
```
Remove `createadmin` from this line, if you don't need admin account

frontend will appear on `http://localhost:80`

#### (Optional) Get yggdrasil node address (if enabled)
```console
docker exec -t yggdrasilgate /docker-entrypoint.sh getaddr
```

### K8S way
See in [/k8s](https://github.com/katzterd/0chan/tree/main/k8s)
