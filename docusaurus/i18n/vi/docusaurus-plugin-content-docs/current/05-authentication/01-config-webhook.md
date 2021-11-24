---
id: config-webhook
title: Config webhook
sidebar_title: Config webhook
---

Hasura sẽ không handle phần authentication (authn) cho chúng ta mà chỉ cấp phần integration thông qua [JWT mode](https://hasura.io/docs/latest/graphql/core/auth/authentication/jwt.html) 
hoặc [Webhook mode](https://hasura.io/docs/latest/graphql/core/auth/authentication/webhook.html).

Hasura Extra cung cấp phần integration authn thông qua webhook mode, nếu so với JWT mode, webhook sẽ không tối ưu performance nhưng bù lại bạn sẽ 
cung cấp được nhiều hình thức authn hơn thay vì chỉ mỗi JWT token (ví dụ: Basic Auth cho first party) 
và [session variables được linh động hơn](./02-session-variable-enhancer.md).

## Đối với Symfony users

:::tip
Nếu như bạn sử dụng [Symfony App](../02-installation/03-symfony-app.md) thì hãy bỏ qua bước bên dưới, vì template đã cấu hình giúp bạn.
:::

Để cấu hình webhook mode bạn cần set `HASURA_GRAPHQL_AUTH_HOOK` env của Hasura container trỏ về url path `/hasura_auth_hook`,
tiếp đến là cấu hình Symfony [security firewall](https://symfony.com/doc/current/security.html#the-firewall) bao phủ `/hasura_auth_hook` path.

Vậy là xong, bạn đã config xong Hasura webhook auth mode cho Symfony rồi đấy. 