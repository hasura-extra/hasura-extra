---
id: state-processor-metadata
title: State processor
sidebar_title: State processor
---

State processor sẽ góp phần `process` application [state](https://en.wikipedia.org/wiki/State_(computer_science)) của bạn lên Hasura. 

:::info
Đây là tính năng nâng cao dành cho những ai muốn xây dựng các processors riêng góp phần khi thực thi [persist application state](./07-persist-application-state.md).
:::

## Đối với Symfony users

Bundle đã cấu hình sẵn [auto configuration](https://symfony.com/doc/current/service_container.html#the-autoconfigure-option) dành cho [StateProcessorInterface](https://github.com/hasura-extra/metadata/blob/main/src/StateProcessorInterface.php), khi
class của bạn implements interface trên thì khi thực hiện [persist application state](./07-persist-application-state.md) nó sẽ được gọi kèm, ví dụ:

```php
use Hasura\Metadata\StateProcessorInterface;

final class ReloadStateProcessor implements StateProcessorInterface
{
    public function process(ManagerInterface $manager, bool $allowInconsistent = false): void
    {
        $manager->reload(true, true);
    }
}
```

Theo ví dụ trên, reload state processor sẽ thực hiện reload local remote schema SDL (nếu có).