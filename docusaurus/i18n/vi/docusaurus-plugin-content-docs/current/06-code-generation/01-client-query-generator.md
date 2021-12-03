---
id: client-query-generator
title: Client query generator
sidebar_title: Client query generator
---

Hasura Extra integrate với [Sailor](https://github.com/spawnia/sailor) typesafe graphql client để generate PHP code từ graphql query spec của bạn,
ứng dụng cho việc khi bạn cần integrate với 3rd parties cũng sử dụng graphql thông qua Hasura [remote schema](../03-handle-business-logic/01-add-remote-schema.md).

## Dành cho Symfony users

Đầu tiên bạn cần run introspect query để sinh ra Hasura SDL:

```shell
php bin/console hasura:sailor:introspect
```

Tiếp đến bạn hãy tạo 1 file query spec `.graphql` trong thư mục `hasura/graphql` và tiến hành generate code thông qua câu lệnh:

```shell
php bin/console hasura:sailor:codegen
```

Client executor được generate sẽ nằm trong thư mục `App\GraphQLExecutor`, thế là xong, ngay bây giờ bạn đã có thể sử dụng executor rồi đấy.

:::tip
Nếu như bạn sử dụng [Laravel hoặc Symfony application template](../02-installation/02-application-template.md) thì hãy kham khảo cách dùng tại `App\Command\GetCountries` class. 
:::