---
id: with-docker-compose
title: Với Docker compose
sidebar_title: Với Docker compose
---

:::info
Tài liệu này dành cho các project bắt đầu bằng [Laravel hoặc Symfony application template](../02-installation/02-application-template.md).
:::


## Tiến hành 

Đầu tiên bạn hãy sử dụng `git clone`, `scp` hoặc bất kỳ tools nào bạn hay sử dụng để đưa source lên trên máy chủ, sau đó đi vào
thư mục chứa source mà bạn vừa đưa lên và start project với tập lệnh sau:

```shell
APP_SECRET=ChangeMe \
HASURA_ADMIN_SECRET=ChangeMe \
POSTGRES_PASSWORD=ChangeMe \
APP_HASURA_SECRET=ChangeMe \
APP_HASURA_BASIC_AUTH="$(printf hasura:${APP_HASURA_SECRET} | base64 -)" \
JWT_SECRET_KEY=ChangeMe \
JWT_PUBLIC_KEY=ChangeMe \
MAILER_DSN=ChangeMe \
docker-compose -f docker-compose.yaml -f docker-compose.prod.yaml up -d
```

Đợi vài giây, tiếp đến là chạy database migrations và [apply Hasura metadata](../07-manage-metadata/02-apply-metadata.md):

```shell
docker-compose -f docker-compose.yaml -f docker-compose.prod.yaml exec apache php bin/console doctrine:migrations:migrate --allow-no-migration --no-interaction; \
docker-compose -f docker-compose.yaml -f docker-compose.prod.yaml exec apache php bin/console hasura:metadata:reload; \
docker-compose -f docker-compose.yaml -f docker-compose.prod.yaml exec apache php bin/console hasura:metadata:apply;
```

Vậy là bạn đã deploy thành công project với Docker compose rồi đấy.

:::info
Đối với môi trường production, Apache và Postgres containers sẽ không publish port chỉ còn Hasura publish trên port 80. 
:::

:::info
Để cấu hình HTTPS bạn hãy kham khảo thêm tại [đây](https://hasura.io/docs/latest/graphql/core/deployment/enable-https.html). 
:::