---
id: graphqlite
title: GraphQLite
sidebar_title: GraphQLite
---

[GraphQLite](https://graphqlite.thecodingmachine.io/) là một bộ thư viện PHP giúp bạn xây dựng GraphQL server thông qua code-first approach
với [PHP8 attribute](https://www.php.net/manual/en/language.attributes.overview.php):

```php
use TheCodingMachine\GraphQLite\Annotations as GQL;

class Resolver
{
    #[GQL\Query(name: 'hello')]
    public function __invoke(): string
    {
        return 'world';
    }
}
```

Với resolver trên GraphQLite sẽ sinh ra schema definition language (SDL) sau:

```graphql
type Query {
    hello: String!
}
```
Hasura Extra tích hợp với GraphQLite để xây dựng GraphQL server handle business logic kết nối với Hasura thông qua [remote schema](../01-remote-schema.md).

