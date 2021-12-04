---
id: with-kubernetes-helm-chart
title: Với Kubernetes Helm chart
sidebar_label: Với Kubernetes Helm chart
---

:::info
Tài liệu này chỉ dành cho các project bắt đầu bằng các [application templates](../02-installation/02-application-templates.md).
:::

## Deploy trên KinD {#deploy-on-kind}

[Kubernetes in Docker](https://kind.sigs.k8s.io/) là cluster local lý tưởng để test thử Helm chart của project trước khi deploy lên môi trường production,
đầu tiên bạn cần setup KinD theo hướng dẫn tại trang chủ, sau đó [setup local registry](https://kind.sigs.k8s.io/docs/user/local-registry/), vậy
là bạn đã chuẩn bị xong môi trường KinD rồi đấy.

Tiếp đến hãy build Docker image:

```shell
docker-compose -f docker-compose.yaml -f docker-compose.prod.yaml build
```

Push image lên local registry:

```shell
docker-compose -f docker-compose.yaml -f docker-compose.prod.yaml push
```

Cập nhật Helm dependencies:

```shell
helm dep update ./charts/api/
```

Và bước cuối cùng là install chart với các cấu hình mặc định:

```shell
helm install api ./charts/api
```

Bạn có thể thay đổi 1 số thống số trực tiếp tại `charts/api/values.yaml`, lưu ý đối với secret bạn không nên lưu trữ tại `values.yaml` mà nên
thiết lập khi install chart thông qua `--set` option hoặc sử dụng các công cụ quản lý secrets như [Binami Sealed Secrets](https://github.com/bitnami-labs/sealed-secrets).

Ví dụ với `--set` option:

```shell
helm install api ./charts/api --set apache.appHasuraSecret=ChangeMe
```

## Deploy trên Kubernetes

Giống với cách [deploy trên KinD](#deploy-on-kind) chỉ khác là chúng ta cần push Docker image lên registry (Dockerhub, ECR, GCR, ACR...),
sau khi push xong bạn hãy set image của `apache` container về repository trên registry mà bạn chọn.

Ví vụ:

```shell
helm install api ./charts/api --set apache.image.repository=...
```