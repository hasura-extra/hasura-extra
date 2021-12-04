---
id: table-event
title: Table event
sidebar_label: Table event
---

Hasura Extra sẽ dispatch [table events triggered](https://hasura.io/docs/latest/graphql/core/event-triggers/index.html) bởi Hasura 
thông qua [PSR-14](https://www.php-fig.org/psr/psr-14/) event dispatcher.

:::info
Khác với Doctrine ORM và Eloquent events, Hasura table event sẽ được dispatch async thông qua webhook request không ãnh hưởng đến
 response time của request mà end-user gọi đến. Cho nên bạn hoàn toàn có thể sử dụng nó thay thế cho một số trường hợp phải sử dụng message queue như send mail hay call first/third parties.

Hasura table event triggered là một nền tảng [change data capture](https://en.wikipedia.org/wiki/Change_data_capture) cho nên bất kỳ
application nào (ví dụ NodeJS app) tương tác với database rows của bạn đều được trigger events.
:::

## Thêm event trigger tại Hasura {#add-event-trigger}

Hướng dẫn cách thêm event trigger trên Hasura bạn có thể xem tại [đây](https://hasura.io/docs/latest/graphql/core/event-triggers/create-trigger.html).

:::info
Khi cài Laravel package url path để handle table event sẽ là `/hasura-table-event` còn đối với
Symfony bundle url path sẽ là `/hasura_table_event`.
:::

:::tip
Nếu như bạn sử dụng [Laravel hoặc Symfony application template](../02-installation/02-application-templates.md) thì hãy sử dụng value `{{APP_BASE_URI}}/hasura-table-event` đối với Laravel và
`{{APP_BASE_URI}}/hasura_table_event` đối với Symfony để làm webhook url mỗi khi thêm event trigger.
:::

## Handling event

Sau khi [thêm event trigger](#add-event-trigger) bạn cần thêm event listener/subscriber để lắng nghe sự kiện mà Hasura gọi vào:

+ [Laravel table event handling](./02-laravel-table-event-handling.md)
+ [Symfony table event handling](./03-symfony-table-event-handling.md)