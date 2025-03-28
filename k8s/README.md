
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

Simple opaque secrets from `.env`:
```console
kubectl create -n <namespace> secret generic <secretsName> --from-env-file=.env
```

##### OR

**(Optional)** Encrypt your secrets with sealed secrets (e.g. for gitops purposes). [Install it first](https://github.com/bitnami-labs/sealed-secrets/releases)
```console
kubeseal --fetch-cert --controller-name=sealed-secrets-controller --controller-namespace=kube-system > pub-sealed-secrets.pem
kubectl create -n <namespace> secret generic <secretsName> --from-env-file=.env --dry-run=client -o yaml > secrets.yaml
kubeseal --format=yaml --cert=pub-sealed-secrets.pem < secrets.yaml > encrypted_secrets.yaml
rm -f secrets.yaml
kubectl apply -f encrypted_secrets.yaml
```

#### 3. (Optional) Create storage class (or use default one)
Examples is located in `examples/sc` directory
```console
kubectl apply -f examples/sc/<provisioner-name>-sc.yaml
```

#### 4. Deploy
```console
helm upgrade --install <my-release> 0chan/0chan \
--repo https://katzterd.github.io/0chan \
-n <namespace> --create-namespace
```

#### 5. Set up db and admin account
```console
kubectl exec -n <namespace> -t deployments/backend -- /src/config/docker-entrypoint.sh createdb createadmin
```
You can simply remove `createadmin` from this line, if you don't need admin account

#### 6. (Optional) Expose to clearnet
Examples is located in `examples/loadbalancer` directory
```console
kubectl apply -f examples/loadbalancer/expose-frontend.yaml
kubectl apply -f examples/loadbalancer/expose-db.yaml
```

### Configuration

See in [./helm/charts/0chan](https://github.com/katzterd/0chan/tree/main/k8s/helm/charts/0chan)
