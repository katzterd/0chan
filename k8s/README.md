
# k8s workflow

## Requirements:
- [kubectl](https://kubernetes.io/docs/tasks/tools/install-kubectl/)
- [helm](https://helm.sh/docs/intro/install/)
- [kubeseal](https://sealed-secrets.netlify.app/) (optional)

## Quick start

#### 1. Prepare .env 
```console
cp .env-dist .env
```
Then fill fields in `.env` by your text editor with desired values

#### 2. Create secrets

```console
kubectl create namespace <namespace>
kubectl create -n <namespace> secret generic <secretsName> --from-env-file=.env
```

#### 3. Deploy

See deploy instruction and configurable options in [./helm/charts/0chan](https://github.com/katzterd/0chan/tree/main/k8s/helm/charts/0chan)

#### 4. Set up db and admin account
```console
kubectl exec -n <namespace> -t deployments/<my-release>-backend -- /src/config/docker-entrypoint.sh createdb createadmin
```
Remove `createadmin` from this line, if you don't need admin account