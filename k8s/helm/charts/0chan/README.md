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

## Configurable options

| Parameter                                    | Description                              | Default Value                 |
| -------------------------------------------- | ---------------------------------------- | ----------------------------- |
| `torgate.enable`                             | "true" to enable torgate                 | None (disabled)               |
| `i2pgate.enable`                             | "true" to enable i2pgate                 | None (disabled)               |
| `yggdrasilgate.enable`                       | "true" to enable yggdrasilgate           | None (disabled)               |
| `registry`                                   | Override Container registry              | `ghcr.io/katzterd/0chan`      |
| `secret`                                     | Override secrets name                    | `0chan-secret`                |
| `db.persistentVolume.storageClass.name`      | Override storageClass name for database  | None (default)                |
| `storage.persistentVolume.storageClass.name` | Override storageClass name for storage   | None (default)                |
| `cache.persistentVolume.storageClass.name`   | Override storageClass name for cache     | None (default)                |
| `db.persistentVolume.size`                   | Size of database free space (in Gi)      | `10Gi`                        |
| `storage.persistentVolume.size`              | Size of storage free space (in Gi)       | `25Gi`                        |
| `cache.persistentVolume.size`                | Size of cache free space (in Gi)         | `5Gi`                         |
| `imagePullSecrets`                           | For pulling from private registry        | None (Array, see values.yaml) |
| `frontend.podAnnotations`                    | Custom Annotations for frontend pod      | None                          |
| `backend.podAnnotations`                     | Custom Annotations for backend pod       | None                          |
| `storage.podAnnotations`                     | Custom Annotations for storage pod       | None                          |
| `db.podAnnotations`                          | Custom Annotations for db pod            | None                          |
| `cache.podAnnotations`                       | Custom Annotations for cache pod         | None                          |
| `torgate.podAnnotations`                     | Custom Annotations for torgate pod       | None                          |
| `i2pgate.podAnnotations`                     | Custom Annotations for i2pgate pod       | None                          |
| `yggdrasilgate.podAnnotations`               | Custom Annotations for yggdrasilgate pod | None                          |
| `frontend.podLabels`                         | Custom Labels for frontend pod           | None                          |
| `backend.podLabels`                          | Custom Labels for backend pod            | None                          |
| `storage.podLabels`                          | Custom Labels for storage pod            | None                          |
| `db.podLabels`                               | Custom Labels for db pod                 | None                          |
| `cache.podLabels`                            | Custom Labels for cache pod              | None                          |
| `torgate.podLabels`                          | Custom Labels for torgate pod            | None                          |
| `i2pgate.podLabels`                          | Custom Labels for i2pgate pod            | None                          |
| `yggdrasilgate.podLabels`                    | Custom Labels for yggdrasilgate pod      | None                          |

### Pulling from private registry

```console
kubectl create -n <namespace> secret generic <imagePullSecretName> \
    --from-file=.dockerconfigjson=/path/to/.docker/config.json \
    --type=kubernetes.io/dockerconfigjson
```

## Get yggdrasil node address (if enabled)

```console
kubectl exec -n <namespace> -t deployments/<my-release>-yggdrasilgate -- /docker-entrypoint.sh getaddr
```
