
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
kubectl create namespace <namespace>
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
kubectl exec -n <namespace> -t deployments/<my-release>-backend -- /src/config/docker-entrypoint.sh createdb createadmin
```
You can simply remove `createadmin` from this line, if you don't need admin account

#### 6. (Optional) Expose to clearnet
Examples is located in `examples/loadbalancer` directory
```console
kubectl apply -f examples/loadbalancer/expose-frontend.yaml
kubectl apply -f examples/loadbalancer/expose-db.yaml
```

Also, you may can install `nginx-ingress` controller in your cluster to deploy several ochko instances on it. Example with Cloudflare CIDR:
```console
helm upgrade --install ingress-nginx ingress-nginx \
    --repo https://kubernetes.github.io/ingress-nginx \
    --namespace ingress-nginx --create-namespace \
    --set controller.service.externalTrafficPolicy=Local \
    --set controller.config.use-forwarded-headers="true" \
    --set controller.config.compute-full-forwarded-for="true" \
    --set controller.config.forwarded-for-header="X-Forwarded-For" \
    --set controller.config.proxy-real-ip-cidr="173.245.48.0/20\,103.21.244.0/22\,103.22.200.0/22\,103.31.4.0/22\,141.101.64.0/18\,108.162.192.0/18\,190.93.240.0/20\,188.114.96.0/20\,197.234.240.0/22\,198.41.128.0/17\,162.158.0.0/15\,104.16.0.0/13\,104.24.0.0/14\,172.64.0.0/13\,131.0.72.0/22\,2400:cb00::/32\,2606:4700::/32\,2803:f800::/32\,2405:b500::/32\,2405:8100::/32\,2a06:98c0::/29\,2c0f:f248::/32"
    --set controller.replicaCount=<number-of-nodes-iyc>
```

Examples of ingress manifests is located in `examples/loadbalancer/ingress` directory
```console
kubectl apply -f examples/loadbalancer/ingress/ingress.yaml
```

### Configuration

See in [./helm/charts/0chan](https://github.com/katzterd/0chan/tree/main/k8s/helm/charts/0chan)
