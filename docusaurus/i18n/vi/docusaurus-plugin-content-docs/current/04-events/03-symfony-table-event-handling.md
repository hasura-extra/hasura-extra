---
id: symfony-table-event-handling
title: Symfony table event handling
sidebar_label: Symfony table event handling
---

Hasura Extra sẽ dispatch event `Hasura\EventDispatcher\TableEvent` bạn cần 
[tạo event listener/subscriber để lắng nghe sự kiện](https://symfony.com/doc/current/event_dispatcher.html) này.

:::tip
Các ví dụ bên dưới đã có sẵn trong [Symfony app](../02-installation/02-application-templates.md) bạn có thể khao khảo và cho chạy thử.
:::

## Event handling

Khởi tạo event subscriber thông qua [maker](https://symfony.com/bundles/SymfonyMakerBundle/current/index.html) command:

```shell
php bin/console make:subscriber WelcomeUserRegisteredSubscriber
```

Với `WelcomeUserRegisteredSubscriber` class như sau:

```php title="src/EventSubscriber/Hasura/WelcomeUserRegisteredSubscriber.php"
namespace App\EventSubscriber\Hasura;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Hasura\EventDispatcher\TableEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class WelcomeUserRegisteredSubscriber implements EventSubscriberInterface
{
    private string $appName;

    private string $emailSender;

    public function __construct(ParameterBagInterface $bag, private MailerInterface $mailer)
    {
        $this->appName = $bag->get('app.name');
        $this->emailSender = $bag->get('app.email_sender');
    }

    public static function getSubscribedEvents()
    {
        return [
            TableEvent::class => 'onTableEvent',
        ];
    }

    public function onTableEvent(TableEvent $event)
    {
        if ('user_registered' !== $event->getTriggerName()) {
            return;
        }

        # Discovery payload: https://hasura.io/docs/latest/graphql/core/event-triggers/payload.html#json-payload
        $event = $event->getEvent();
        $eventData = $event['data']['new'];

        $welcomeEmail = new Email();
        $welcomeEmail->from($this->emailSender);
        $welcomeEmail->to($eventData['email']);
        $welcomeEmail->subject($this->appName);
        $welcomeEmail->html(sprintf('<h1>Hi %s, welcome to %s</h1>', $eventData['name'], $this->appName));

        $this->mailer->send($welcomeEmail);
    }
}
```

Như ví dụ trên với event trigger tên là `user_registered`, mỗi khi user registered (inserted) Hasura
sẽ trigger webhook đến url path: `/hasura_table_event` và từ đó dispatcher sẽ dispatch sự kiện `Hasura\EventDispatcher\TableEvent`, subscriber
sẽ gửi mail welcome đến end-user.

:::tip
Nếu như bạn chưa biết cách tạo event trigger thì có thể [xem tài liệu](./01-table-event.md#add-event-trigger)
:::

## Security config

Như bạn thấy route path: `/hasura_table_event` bất kỳ ai cũng có thể send request đến nó, bạn cần phải cấu hình security
cho nó để cho chỉ có Hasura mới có thể request đến route path này, có rất nhiều cách để cấu hình, trong tài liệu này chúng ta
sẽ sử dụng basic authentication để xác minh request đến từ Hasura.

Cấu hình [basic authentication](https://symfony.com/doc/current/security.html#http-basic)
với [memory user provider](https://symfony.com/doc/current/security/user_providers.html#security-memory-user-provider)
để xác minh request đến từ Hasura:

```yaml title="config/packages/security.yaml"
security:
    enable_authenticator_manager: true
    password_hashers:
        Symfony\Component\Security\Core\User\InMemoryUser: 'plaintext'
    providers:
        ...
        hasura:
            memory:
                users:
                    hasura: { password: '%env(APP_HASURA_SECRET)%' }
    firewalls:
        ...
        table_event:
            pattern: ^/hasura_table_event$
            stateless: true
            provider: hasura
            http_basic:
                realm: Hasura Area
    access_control:
         ...
         - { path: ^/hasura_table_event$, roles: IS_AUTHENTICATED_FULLY }

```

Sau đó khi bạn [thêm event trigger tại Hasura](./01-table-event.md#add-event-trigger), bạn cần thêm basic auth header:

![authorization header](../assets/config-webhook-authorization-header.png)

Lưu ý theo ví dụ trên bạn cần config `APP_HASURA_SECRET` env của application và `APP_HASURA_BASIC_AUTH` env của `hasura` service container.
Ví dụ với `APP_HASURA_SECRET` là `!ChangeMe!` thì `APP_HASURA_BASIC_AUTH` sẽ là `Basic: aGFzdXJhOiFDaGFuZ2VNZSE=` (base64_encode('hasura:!ChangeMe!')).
