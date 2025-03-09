
# k8s workflow

## Requirements:
- [kubectl](https://kubernetes.io/docs/tasks/tools/install-kubectl/)
- [helm](https://helm.sh/docs/intro/install/)
- [kubeseal](https://sealed-secrets.netlify.app/) (optional)

## Setup

#### 1. Prepare .env 
```console
cp .env-dist .env
```
Then fill fields in `.env` by your text editor with needed values

#### 2. Create namespace
```console
kubectl create ns <namespace>
```

#### 3. Create secrets

**(Optional)** Encrypt your secrets with sealed secrets. [Install it first](https://github.com/bitnami-labs/sealed-secrets/releases)
```console
kubeseal --fetch-cert --controller-name=sealed-secrets-controller --controller-namespace=kube-system > pub-sealed-secrets.pem
kubectl create -n <namespace> secret generic <secretsName> --from-env-file=.env --dry-run=client -o yaml > secrets.yaml
kubeseal --format=yaml --cert=pub-sealed-secrets.pem < secrets.yaml > encrypted_secrets.yaml
rm -f secrets.yaml
kubectl apply -f encrypted_secrets.yaml
```

Or you can just create opaque secrets:
```console
kubectl create -n <namespace> secret generic <secretsName> --from-env-file=.env
```

#### 4. Create storage class (or use default one)
Examples is located in `examples/sc` directory
```console
kubectl apply -f examples/sc/<provisioner-name>-sc.yaml
```

#### 5. Deploy
```console
helm repo add 0chan https://katzterd.github.io/k8s/helm
helm repo update
helm install <my-release> -n <namespace> 0chan/0chan
```

#### 6. Set up db and admin account
```console
kubectl exec -n <namespace> -t deployments/backend -- /src/config/docker-entrypoint.sh createdb createadmin
```
You can simply remove `createadmin` from this line, if you don't need admin account

#### 7. (Optional) Expose to clearnet
Example is located in `examples/` directory
```console
kubectl apply -f examples/lb.yaml
```

#### 8. (Optional) Get yggdrasil node address (if enabled)
```console
kubectl exec -n <namespace> -t deployments/yggdrasil -- /docker-entrypoint.sh getaddr
```

### Configuration

See in [./helm/charts/0chan](https://github.com/katzterd/0chan/tree/main/k8s/helm/charts/0chan)
