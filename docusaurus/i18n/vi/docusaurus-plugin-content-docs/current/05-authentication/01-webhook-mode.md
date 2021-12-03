---
id: webhook-mode
title: Webhook mode
sidebar_title: Webhook mode
---

Hasura sẽ không handle phần authentication (authn) cho chúng ta mà chỉ cấp phần integration thông qua [JWT mode](https://hasura.io/docs/latest/graphql/core/auth/authentication/jwt.html) 
hoặc [Webhook mode](https://hasura.io/docs/latest/graphql/core/auth/authentication/webhook.html).

Hasura Extra cung cấp phần integration authn thông qua webhook mode, nếu so với JWT mode, webhook sẽ không tối ưu performance nhưng bù lại bạn có thể 
cung cấp được bất kỳ hình thức authn tùy theo yêu cầu của project, thay vì chỉ mỗi JWT token và [session variables được linh động hơn](./02-session-variable-enhancer.md).

## Cấu hình webhook mode

:::tip
Nếu như bạn sử dụng [Laravel hoặc Symfony application template](../02-installation/02-application-template.md) thì hãy bỏ qua tài liệu này vì template đã cấu hình giúp bạn.
:::

### Đối với Laravel users

Để cấu hình webhook mode bạn cần set `HASURA_GRAPHQL_AUTH_HOOK` env của Hasura container trỏ về url path `/hasura-auth-hook` và cập nhật
`guard` trong file config `config/hasura.php` nếu như bạn sử dụng multi auth guards.

### Đối với Symfony users

Để cấu hình webhook mode bạn cần set `HASURA_GRAPHQL_AUTH_HOOK` env của Hasura container trỏ về url path `/hasura_auth_hook`,
tiếp đến là cấu hình Symfony [security firewall](https://symfony.com/doc/current/security.html#the-firewall) bao phủ `/hasura_auth_hook` path,
bạn có thể kham khảo thêm tại cách cấu hình [JWT authentication](./05-symfony-jwt-authentication.md).

## `X-Hasura-Role` header

Khi client send request đến Hasura có thể gửi kèm `X-Hasura-Role` header để chỉ định role cho request, nếu như user identifier
của request KHÔNG sở hữu role được chỉ định thì request sẽ bị chặn lại.