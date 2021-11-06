[English](./README.md) | Tiếng Việt

Api Client
==========

Thư viện dùng để tương tác với Hasura [api](https://hasura.io/docs/latest/graphql/core/api-reference/index.html).

Cài đặt
------------

Cài đặt thông qua [Composer](https://getcomposer.org/):

```shell
composer require hasura-extra/api-client
```

Cách sử dụng
------

Khởi tạo và gọi api:

```php
<?php

$client = new \Hasura\ApiClient\Client('Hasura base uri của bạn', 'Hasura admin secret của bạn (bỏ qua nếu không thiết lập)');

// Get Hasura config
$config = $client->config()->get();

// Export Hasura metadata
$metadata = $client->metadata()->query('export_metadata', []);

// Execute graphql query:
$data = $client->graphql()->query('query { __typename }');

// And more...
```

Các methods của client dùng để tương tác với Hasura api:

Method            | Api reference
-------------     | --------------------
config            | [link](https://hasura.io/docs/latest/graphql/core/api-reference/config.html)
graphql           | [link](https://hasura.io/docs/latest/graphql/core/api-reference/graphql-api/index.html)
relay graphql     | [link](https://hasura.io/docs/latest/graphql/core/api-reference/relay-graphql-api/index.html)
metadata          | [link](https://hasura.io/docs/latest/graphql/core/api-reference/metadata.html)
version           | [link](https://hasura.io/docs/latest/graphql/core/api-reference/version.html)

