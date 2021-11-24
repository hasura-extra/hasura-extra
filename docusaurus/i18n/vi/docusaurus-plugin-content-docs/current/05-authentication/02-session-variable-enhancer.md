---
id: session-variable-enhancer
title: Session variable enhancer
sidebar_title: Session variable enhancer
---

[Session variables](https://hasura.io/docs/latest/graphql/core/auth/authorization/roles-variables.html#dynamic-session-variables) là
các variables của 1 phiên request dùng để định danh user và các thông số đi kèm nhằm phục vụ cho authorization (authz).

:::info
Nếu như hệ thống authentication (authn) của bạn không sử dụng [webhook mode](./01-config-webhook.md) thì hãy bỏ qua tài liệu này,
vì toàn bộ session variables của end-user sẽ store trong JWT.
:::

Hasura Extra webhook authn cung cấp [session variable enhancer](https://github.com/hasura-extra/auth-hook/blob/main/src/SessionVariableEnhancerInterface.php) interface,
dùng để building session variables cho phiên request của end-user.

## Đối với Symfony users

Mặc định bundle cấu hình sẵn auto configuration cho interface `Hasura\AuthHook\SessionVariableEnhancerInterface` nên chỉ cần bạn tạo
class implements interface trên sẽ được mark là enhancer và góp phần xây dựng session variables cho request, ví dụ:

```php
namespace App\Security;

use Hasura\AuthHook\SessionVariableEnhancerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Security\Core\Security;

final class AuthenticatedSessionVariableEnhancer implements SessionVariableEnhancerInterface
{
    public function __construct(private Security $security)
    {
    }

    public function enhance(array $sessionVariables, ServerRequestInterface $request): array
    {
        if ($user = $this->security->getUser()) {
            $sessionVariables['x-hasura-user-id'] = $user->getUserIdentifier();
        }

        return $sessionVariables;
    }
}
```

Theo ví dụ trên chúng ta tạo ra 1 enhancer để chèn `X-Hasura-User-Id` cho request dựa trên user identifier đã đăng nhập.