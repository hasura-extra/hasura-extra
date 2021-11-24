---
id: handle-table-event
title: Handle table event
sidebar_title: Handle table event
---

Hasura Extra sẽ dispatch [table events triggered](https://hasura.io/docs/latest/graphql/core/event-triggers/index.html) bởi Hasura 
thông qua [PSR-14](https://www.php-fig.org/psr/psr-14/) event dispatcher.

Mặc định khi cài Symfony bundle url path để handle table event sẽ là `/hasura_table_event` bạn sẽ cần url path này ở bước [thêm remote schema](#add-event-trigger).

## Thêm event trigger tại Hasura {#add-event-trigger}

Đầu tiên bạn cần thêm event trigger trên Hasura xem hướng dẫn tại [đây](https://hasura.io/docs/latest/graphql/core/event-triggers/create-trigger.html).

:::tip
Nếu như bạn sử dụng [Symfony App](../02-installation/03-symfony-app.md) thì hãy sử dụng value `{{APP_BASE_URI}}/hasura_table_event` làm 
webhook url mỗi khi thêm event trigger.
:::

## Dành cho Symfony users

[Event Dispatcher](https://symfony.com/doc/current/event_dispatcher.html) của Symfony cũng implements **PSR-14** nên việc handle event của Hasura sẽ không khác gì với các system event mà bạn hay handle
(ví dụ `kernel.request`). Mỗi khi Hasura trigger table event, dispatcher sẽ dispatch event `Hasura\EventDispatcher\TableEvent`, bạn
chỉ cần subscribe/listen sự kiện trên để chèn business logic, ví dụ:

```php
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
        if ($event->getTriggerName() !== 'user_registered') {
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

Như ví dụ trên, với event trigger tên là `user_registered` được tạo ở [bước đầu tiên](#add-event-trigger), mỗi khi user registered Hasura
sẽ trigger webhook đến url path: `/hasura_table_event` và từ đó dispatcher sẽ dispatch sự kiện `Hasura\EventDispatcher\TableEvent`, chúng ta
handle gửi mail đến end-user.
