---
id: add-remote-schema
title: Thêm remote schema
sidebar_title: Thêm remote schema
---

Hasura giúp chúng ta rất nhiều trong vấn đề CRUD và authorization, tuy nhiên nó không thể giúp chúng ta handle business
logic vì mỗi dự án sẽ có các đặc thù riêng (ví dụ: validation data, gọi 3rd parties API...). 
Chính vì lý do đó chúng ta chỉ nên sử dụng read operation của Hasura (đôi khi cũng sẽ xài delete nếu không có logic cầu kỳ), 
còn riêng đối với write operation (chủ yếu là update/insert) thì chúng ta sẽ xây dựng [remote schema](https://hasura.io/docs/latest/graphql/core/remote-schemas/index.html) để handle.

Mô phỏng (nguồn [Hasura](https://hasura.io)):
![Copyright https://hasura.io](../assets/remote-schema.png)

## Thêm remote schema

:::tip
Nếu như project của bạn bắt đầu từ [Symfony App](../02-installation/03-symfony-app.md) thì bạn có thể bỏ qua hướng dẫn này vì mặc định
chúng tôi đã thêm sẵn giúp bạn.
:::

Để handle business logic bạn cần thêm remote schema trỏ về GraphQL server ([GraphQLite](./02-graphqlite.md)) url path sẽ là `/graphql`,
xem hướng dẫn tại [đây](https://hasura.io/docs/latest/graphql/core/remote-schemas/adding-schema.html).

## Cấu hình remote schema name

:::tip
Nếu như project của bạn bắt đầu từ [Symfony App](../02-installation/03-symfony-app.md) thì bạn có thể bỏ qua hướng dẫn này vì mặc định
chúng tôi đã thêm sẵn giúp bạn.
:::

Sau khi thêm remote schema ở bước trên bạn cần cấu hình remote schema name cho project của bạn.

+ Đối với Symfony bundle bạn cần cấu hình `hasura.remote_schema_name` tại `config/packages/hasura.yaml`