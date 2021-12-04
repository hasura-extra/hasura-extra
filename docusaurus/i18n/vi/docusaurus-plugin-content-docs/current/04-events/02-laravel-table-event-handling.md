---
id: laravel-table-event-handling
title: Laravel table event handling
sidebar_label: Laravel table event handling
---

Hasura Extra sẽ dispatch event `Hasura\EventDispatcher\TableEvent` bạn cần [tạo listener/subscriber để lắng nghe sự kiện](https://laravel.com/docs/8.x/events) này.

:::tip
Các ví dụ bên dưới đã có sẵn trong [Laravel app](../02-installation/02-application-templates.md) bạn có thể khao khảo và cho chạy thử.
:::

## Event handling

Khởi tạo event listener thông qua [make](https://laravel.com/docs/8.x/events#generating-events-and-listeners) command:

```bash
php artisan make:listener UserRegistered --event="\Hasura\EventDispatcher\TableEvent"
```

Với `UserRegistered` class như sau:

```php title="app/Listeners/UserRegistered.php"
<?php

namespace App\Listeners;

use App\Mail\WelcomeMail;
use Hasura\EventDispatcher\TableEvent;
use Illuminate\Support\Facades\Mail;

class UserRegistered
{
    public function handle(TableEvent $event)
    {
        if ($event->getTriggerName() !== 'user_registered') {
            return;
        }

        # Discovery payload: https://hasura.io/docs/latest/graphql/core/event-triggers/payload.html#json-payload
        $event = $event->getEvent();
        $eventData = $event['data']['new'];

        Mail::to($eventData['email'])->send(new WelcomeMail($eventData['name']));
    }
}
```

Và `App\Mail\WelcomeMail` như sau:

```php title="app/Mail/WelcomeMail.php"
<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private string $name)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->html(
            sprintf('<h1>Hi %s, welcome to %s</h1>', $this->name, config('app.name'))
        );
    }
}
```

Tiếp đến bạn cần thêm sự kiện `Hasura\EventDispatcher\TableEvent` trong `App\Providers\EventServiceProvider`:

```php title="app/Providers/EventServiceProvider.php"
use App\Listeners\UserRegistered;
use Hasura\EventDispatcher\TableEvent;

/**
 * The event listener mappings for the application.
 *
 * @var array
 */
protected $listen = [
    TableEvent::class => [
        UserRegistered::class,
    ],
];
```

Như ví dụ trên với event trigger tên là `user_registered`, mỗi khi user registered (inserted) Hasura
sẽ trigger webhook đến url path: `/hasura-table-event` và từ đó dispatcher sẽ dispatch sự kiện `Hasura\EventDispatcher\TableEvent`, listener
sẽ gửi mail welcome đến end-user.

:::tip
Nếu như bạn chưa biết cách tạo event trigger thì có thể [xem tài liệu](./01-table-event.md#add-event-trigger)
:::


## Security config

Như bạn thấy route path: `/hasura-table-event` bất kỳ ai cũng có thể send request đến nó, bạn cần phải cấu hình security
cho nó để cho chỉ có Hasura mới có thể request đến route path này, có rất nhiều cách để cấu hình, trong tài liệu này chúng ta
sẽ sử dụng [basic authentication](https://en.wikipedia.org/wiki/Basic_access_authentication) để xác minh request đến từ Hasura.

Chúng tôi cung cấp sẵn cho bạn `hasura` guard với username fixed là `hasura` và password với giá trị của `app_secret` (mặc định là `APP_HASURA_SECRET` env) trong file `config/hasura.php`,
bạn chỉ cần thêm `auth:hasura` middleware cho `routes.table_event` trong file `config/hasura.php`:

```php title="config/hasura.php"
'table_event' => [
    /*
     * Enabled table event handle endpoint, disable it when you not use Hasura event triggered.
     */
    'enabled' => true,
    /*
     * Route uri.
     */
    'uri' => '/hasura-table-event',
    /*
     * Set of route middleware,
     * `hasura` guard will be use basic auth with fixed user `hasura` and password's `app_secret` config value above.
     */
    'middleware' => ['auth:hasura']
]
```

Sau đó khi bạn [thêm event trigger tại Hasura](./01-table-event.md#add-event-trigger), bạn cần thêm basic auth header:

![authorization header](../assets/config-webhook-authorization-header.png)

Lưu ý theo ví dụ trên bạn cần config `APP_HASURA_SECRET` env của application và `APP_HASURA_BASIC_AUTH` env của `hasura` service container.
Ví dụ với `APP_HASURA_SECRET` là `!ChangeMe!` thì `APP_HASURA_BASIC_AUTH` sẽ là `Basic: aGFzdXJhOiFDaGFuZ2VNZSE=` (base64_encode('hasura:!ChangeMe!')).
