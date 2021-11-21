Api Client
==========

Library to help interact with Hasura [api](https://hasura.io/docs/latest/graphql/core/api-reference/index.html).

Installation
------------

Install via [Composer](https://getcomposer.org/):

```shell
composer require hasura-extra/api-client
```

Usages
------

Create client and call api:

```php
<?php

$client = new \Hasura\ApiClient\Client('Your Hasura base uri', 'Your Hasura admin secret (optional)');

// Get Hasura config
$config = $client->config()->get();

// Export Hasura metadata
$metadata = $client->metadata()->query('export_metadata', []);

// Execute graphql query:
$data = $client->graphql()->query('query { __typename }');

// And more...
```

Client method provides to interact with Hasura api:

Method            | Api reference
-------------     | --------------------
config            | [link](https://hasura.io/docs/latest/graphql/core/api-reference/config.html)
graphql           | [link](https://hasura.io/docs/latest/graphql/core/api-reference/graphql-api/index.html)
relay graphql     | [link](https://hasura.io/docs/latest/graphql/core/api-reference/relay-graphql-api/index.html)
metadata          | [link](https://hasura.io/docs/latest/graphql/core/api-reference/metadata.html)
version           | [link](https://hasura.io/docs/latest/graphql/core/api-reference/version.html)

License
-------

This project is released under the [MIT License](./LICENSE).