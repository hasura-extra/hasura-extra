---
id: getting-started
title: Bắt đầu
sidebar_title: Bắt đầu
---

Hasura Extra là bộ tập hợp các thư viện PHP độc lập có thể sử dụng ở bất kỳ project nào, ngoài ra chúng tôi còn có cung cấp sẵn
Symfony bundle dành cho Symfony users, sẽ hổ trợ Laravel trong thời gian tới.

## Chuẩn bị

Về môi trường, bạn cần chuẩn bị Docker engine và Docker compose version 3.

Về kiến thức, nếu như bạn chưa biết về [GraphQL](https://graphql.org/) hoặc [Hasura](https://hasura.io/) graphql engine,
thì bạn nên bắt đầu từ [hướng dẫn](/tutorial/introduction) của chúng tôi, nếu bạn đã quen với 2 concepts trên thì có thể
bỏ qua bài hướng dẫn và chọn cách cài đặt phù hợp với project của bạn.

## Đối với Symfony users

Nếu như project của bạn là một project mới thì bạn nên sử dụng [Symfony App](./03-symfony-app.md), đây là một boilerplate được cài đặt sẵn các
cấu hình cần thiết cho việc integrate giữa Symfony và Hasura như local [remote schema](https://hasura.io/docs/latest/graphql/core/remote-schemas/index.html), 
handle [event triggered](https://hasura.io/docs/latest/graphql/core/event-triggers/index.html) bởi Hasura, tổ chức sắp xếp Hasura metadata, 
Sailor graphql client code generator, authentication hook, JWT... và có example giúp bạn dễ tiếp cận hơn.

Mặt khác bạn có thể cài đặt bundle thông qua Composer:

```shell
composer require hasura-extra/bundle
```

Sau khi cài đặt thông qua Composer xong, Symfony Flex sẽ giúp bạn cấu hình config và routes, sau đó bạn cần mở file
`config/packages/hasura.yaml` để cấu hình Hasura base uri, admin secret.

