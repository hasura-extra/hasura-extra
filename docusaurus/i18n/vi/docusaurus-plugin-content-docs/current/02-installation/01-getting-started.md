---
id: getting-started
title: Bắt đầu
sidebar_title: Bắt đầu
---

Hasura Extra là bộ tập hợp các thư viện PHP độc lập có thể sử dụng ở bất kỳ project nào. Ngoài ra chúng tôi còn có cung cấp 
package/bundle và application tempate (boilerplate) dành cho Laravel và Symfony users.

## Chuẩn bị

Về môi trường, bạn cần chuẩn bị Docker engine và Docker compose version 3.

Về kiến thức, nếu như bạn chưa biết về [GraphQL](https://graphql.org/) hoặc [Hasura](https://hasura.io/) graphql engine,
thì bạn nên bắt đầu từ [hướng dẫn](/tutorial/introduction) của chúng tôi, nếu bạn đã quen với 2 concepts trên thì có thể
bỏ qua bài hướng dẫn và chọn cách cài đặt phù hợp với project của bạn.

## Cài đặt thông qua application templates

Nếu như project của bạn là một project mới thì bạn nên sử dụng [Laravel hoặc Symfony application template](./02-application-templates.md), đây là 2 bộ boilerplate dành riêng cho Laravel và Symfony framework được cài đặt sẵn các
cấu hình cần thiết cho việc integrate giữa framework và Hasura như local [remote schema](https://hasura.io/docs/latest/graphql/core/remote-schemas/index.html),
handle [event triggered](https://hasura.io/docs/latest/graphql/core/event-triggers/index.html) bởi Hasura, tổ chức sắp xếp Hasura metadata,
Sailor graphql client code generator, authentication hook, Sanctum/JWT authentication, Helm chart dùng để deploy dự án của bạn lên Kubernetes và có example giúp bạn dễ tiếp cận hơn.

Mặt khác bạn có thể cài đặt package/bundle thông qua [Composer](https://getcomposer.org).

## Cài đặt thông qua Composer

### Đối với Laravel users

Cài đặt thông qua composer:

```shell
composer require hasura-extra/laravel
```

Sau đó bạn cần publish Hasura Extra config:

```shell
php artisan vendor:publish --provider="Hasura\Laravel\ServiceProvider\HasuraServiceProvider"
```

Và publish [Laravel GraphQLite](https://graphqlite.thecodingmachine.io/docs/laravel-package) config:

```shell
php artisan vendor:publish --provider="TheCodingMachine\GraphQLite\Laravel\Providers\GraphQLiteServiceProvider"
```

Sau khi publish config files, bạn hãy kham khảo chú thích bên trong để cấu hình cho phù hợp với project của bạn.

### Đối với Symfony users

Cài đặt thông qua composer:

```shell
composer require hasura-extra/bundle
```

Symfony Flex sẽ giúp bạn cấu hình config và routes, sau đó bạn cần mở file
`config/packages/hasura.yaml` để cấu hình Hasura base uri, admin secret.

