# 0chan Helm Chart
![CI](https://img.shields.io/github/actions/workflow/status/katzterd/0chan/helm-build.yml?label=Chart&logo=helm&style=for-the-badge)

## Get Repository

```console
helm repo add 0chan https://katzterd.github.io/0chan
helm repo update
```

## Install chart

```console
helm install <my-release> (--set <key1=val1,key2=val2,...>) 0chan/0chan -n <namespace> --create-namespace
```

## Uninstall chart

```console
helm delete -n <namespace> <my-release>
```

## Configuration

| Parameter                                  | Description                                   | Default Value                                           |
|--------------------------------------------|-----------------------------------------------|---------------------------------------------------------|
| `svc.torgate.enable`                       | "true" to enable torgate                      | None (Disabled)                                         |
| `svc.i2pgate.enable`                       | "true" to enable i2pgate                      | None (Disabled)                                         |
| `svc.yggdrasilgate.enable`                 | "true" to enable yggdrasilgate                | None (Disabled)                                         |
| `pvc.db.pvcName`                           | Override db pvc name                          | `db-pv`                                                 |
| `pvc.storage.pvcName`                      | Override storage pvc name                     | `storage-pv`                                            |
| `registry`                                 | Override Container registry                   | `ghcr.io/katzterd/0chan`                                |
| `secretsName`                              | Override secrets name                         | `0chan-secrets`                                         |
| `storageClass.name`                        | Override storage class name                   | `0chan-sc`                                              |
| `dbSpace`                                  | Size of database free space (in Gi)           | `10Gi`                                                  |
| `storageSpace`                             | Size of storage free space (in Gi)            | `25Gi`                                                  |
| `imagePullSecretName`                      | For pulling from private registry             | None                                                    |


### Pulling from private registry
```console
kubectl create -n <namespace> secret generic <imagePullSecretName> \ 
    --from-file=.dockerconfigjson=/path/to/.docker/config.json \
    --type=kubernetes.io/dockerconfigjson
```

## Get yggdrasil node address (if enabled)
```console
kubectl exec -n <namespace> -t deployments/yggdrasil -- /docker-entrypoint.sh getaddr
```
