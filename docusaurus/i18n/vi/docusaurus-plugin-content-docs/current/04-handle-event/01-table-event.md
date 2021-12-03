---
id: table-event
title: Table event
sidebar_title: Table event
---

Hasura Extra sẽ dispatch [table events triggered](https://hasura.io/docs/latest/graphql/core/event-triggers/index.html) bởi Hasura 
thông qua [PSR-14](https://www.php-fig.org/psr/psr-14/) event dispatcher.

:::info
Mặc định khi cài Laravel package url path để handle table event sẽ là `/hasura-table-event` đối với
Symfony bundle url path sẽ là `/hasura_table_event`, bạn sẽ cần url path này ở bước [thêm event trigger](#add-event-trigger).
:::


## Thêm event trigger tại Hasura {#add-event-trigger}

Đầu tiên bạn cần thêm event trigger trên Hasura xem hướng dẫn tại [đây](https://hasura.io/docs/latest/graphql/core/event-triggers/create-trigger.html).

:::tip
Nếu như bạn sử dụng [Laravel hoặc Symfony application template](../02-installation/02-application-templates.md) thì hãy sử dụng value `{{APP_BASE_URI}}/hasura-table-event` đối với Laravel và
`{{APP_BASE_URI}}/hasura_table_event` đối với Symfony để làm webhook url mỗi khi thêm event trigger.
:::

## Handling event

Sau khi [thêm event trigger](#add-event-trigger) bạn cần thêm event listener/subscriber để lắng nghe sự kiện mà Hasura gọi vào:

+ [Laravel table event handling](./02-laravel-table-event-handling.md)
+ [Symfony table event handling](./03-symfony-table-event-handling.md)