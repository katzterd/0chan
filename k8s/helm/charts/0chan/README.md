# 0chan Helm Chart

## Get Repository

```console
helm repo add 0chan https://katzterd.github.io/k8s/helm
helm repo update
```

## Install chart

```console
helm install <my-release> -n <namespace> (--set <key1=val1,key2=val2,...>) 0chan/0chan
```

## Uninstall chart

```console
helm delete -n <namespace> <my-release>
```

### Pulling from private registry
```console
kubectl create -n <namespace> secret generic <imagePullSecretName> \ 
    --from-file=.dockerconfigjson=/path/to/.docker/config.json \
    --type=kubernetes.io/dockerconfigjson
```

### Configuration

Parameter                                           Description                                         Default value

`registry`                                          Override Container registry                         `ghcr.io/katzterd/0chan`
`secretsName`                                       Override secrets name                               `nullchan-secrets`
`storageClass.name`                                 Override storage class name                         `0chan-default-sc`
`dbSpace`                                           Size of database free space (in Gi)                 `10Gi`
`StorageSpace`                                      Size of storage free space (in Gi)                  `25Gi`
`imagePullSecretName`                               For pulling from private registry                    None
