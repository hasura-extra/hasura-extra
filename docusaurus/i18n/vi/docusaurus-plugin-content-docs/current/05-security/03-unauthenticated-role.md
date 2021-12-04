---
id: unauthenticated-role
title: Unauthenticated role
sidebar_title: Unauthenticated role
---

Đối với Hasura, mọi request đều có role kể cả [unauthenticated request](https://hasura.io/docs/latest/graphql/core/auth/authentication/unauthenticated-access.html).

Hasura Extra giúp bạn cấu hình role cho public access request (anonymous user).

:::info
Nếu như bạn xài [JWT mode](https://hasura.io/docs/latest/graphql/core/auth/authentication/jwt.html) thì hãy config anonymous role thông qua `HASURA_GRAPHQL_UNAUTHORIZED_ROLE` env.
:::

## Đối với Symfony users

Mặc định bundle đã config unauthenticated role giúp bạn là `ROLE_ANONYMOUS` nếu bạn muốn thay đổi lại cho phù hợp thì hãy vào file
config `config/packages/hasura.yaml` và thay đổi `hasura.auth.anonymous_role`.