---
id: graphqlite-custom-scalar-types
title: Custom scalar types
sidebar_title: Custom scalar types
---

Hasura Extra cung cấp [custom scalar types](https://graphqlite.thecodingmachine.io/docs/custom-types#registering-a-custom-scalar-type-advanced) compatible với Hasura giúp cho việc tạo
[data federation](https://hasura.io/docs/latest/graphql/core/databases/postgres/schema/remote-relationships/remote-schema-relationships.html)
thuận tiện hơn:

+ json
+ jsonb
+ timestamptz
+ timetz
+ date
+ uuid

Ngoài ra bạn còn có thể ứng dụng các type trên để validate user input format.

Cách sử dụng:

```php
#[Query(name: 'test_scalar', outputType: 'json')]
public function __invoke(
    #[UseInputType(inputType: 'date')] ?\DateTimeInterface $date = null,
    #[UseInputType(inputType: 'json')] ?array $json = null,
    #[UseInputType(inputType: 'jsonb')] ?array $jsonb = null,
    #[UseInputType(inputType: 'timestamptz')] ?\DateTimeInterface $timestamptz = null,
    #[UseInputType(inputType: 'timetz')] ?\DateTimeInterface $timetz = null,
    ?Uuid $uuid = null,
): array {
    return compact(
        'date', 
        'json', 
        'jsonb', 
        'timestamptz',
        'timetz',
        'uuid'
    );
}
```