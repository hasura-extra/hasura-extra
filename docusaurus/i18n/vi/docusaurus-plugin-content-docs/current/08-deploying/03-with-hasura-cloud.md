---
id: with-hasura-cloud
title: Với Hasura cloud
sidebar_title: Với Hasura cloud
---

[Hasura Cloud](https://cloud.hasura.io/) là một SaaS system, bạn không cần bận tâm đến làm thế nào để deploy Hasura của bạn lên production,
vì mọi thứ đã setup giúp bạn.

:::info
Tài liệu này dành cho các project bắt đầu bằng [Symfony App](../02-installation/03-symfony-app.md).
:::

## Đối với Docker compose

Đầu tiên bạn cần xóa `hasura` container trong `docker-compose.yaml` (do chúng ta đã sử dụng Hasura cloud).

Sau đó tiến hành dựng dự án trên Hasura cloud, bạn hãy thiết lập env `APP_BASE_URI` của Hasura về uri trên host của bạn và set
`HASURA_BASE_URI`, `HASURA_ADMIN_SECRET` envs của Apache với giá trị là uri mà Hasura cloud cấp cho bạn.

Ví dụ, chúng ta có các uri sau:

+ Hasura cloud api: `https://xxxx-xx.hasura.app` (lưu ý hãy bỏ path: `/v1/graphql` chỉ lấy base uri)
+ Hasura cloud admin secret: `xxx`
+ Apache: `https://app.example`

Thì chúng ta set `APP_BASE_URI` trên Hasura cloud với giá trị `https://app.example` 
và set `HASURA_BASE_URI`, `HASURA_ADMIN_SECRET` envs của Apache container với giá trị lần lượt là `https://xxxx-xx.hasura.app` và `xxx`.

## Đối với Kubernetes Helm chart

Đầu tiên bạn cần xóa `hasura` chart trong `charts/api/Chart.yaml` và những thứ liên quan đến Hasura trong `charts/api/values.yaml` (do chúng ta đã sử dụng Hasura cloud).

Sau đó tiến hành dựng dự án trên Hasura cloud, bạn hãy thiết lập env `APP_BASE_URI` của Hasura về uri trên host của bạn và set
`HASURA_BASE_URI`, `HASURA_ADMIN_SECRET` envs của Apache với giá trị là uri và admin secret mà Hasura cloud cấp cho bạn.

Ví dụ, chúng ta có các uri sau:

+ Hasura cloud api: `https://xxxx-xx.hasura.app` (lưu ý hãy bỏ path: `/v1/graphql` chỉ lấy base uri)
+ Hasura cloud admin secret: `xxx`
+ Apache: `https://app.example`

Thì chúng ta set `APP_BASE_URI` trên Hasura cloud với giá trị `https://app.example`, đồng thời khi install Helm chart 
ta sẽ set `apache.hasuraBaseUri` là `https://xxxx-xx.hasura.app` và `apache.hasuraAdminSecret` là `xxx`

Ví dụ:

```shell
helm install api ./charts/api \
  --set apache.hasuraBaseUri="https://xxxx-xx.hasura.app" \
  --set apache.hasuraAdminSecret="xxx"
```