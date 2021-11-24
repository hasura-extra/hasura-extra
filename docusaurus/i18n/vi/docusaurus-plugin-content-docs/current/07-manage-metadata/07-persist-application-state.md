---
id: persist-application-state-metadata
title: Persist application state
sidebar_title: Persist application state
---

Persist application [state](https://en.wikipedia.org/wiki/State_(computer_science)) sẽ giúp việc đồng bộ cấu hình giữa application
của bạn với Hasura metadata (ví dụ: remote schema permissions roles, inherited roles, remote schema SDL), việc đồng bộ này sẽ giúp
đảm bảo tính consistent giữa application với Hasura.

## Đối với Symfony users

Giả sử như application của bạn có mutation field `user_registration` dành cho `ROLE_ANONYMOUS` như sau:

```php
namespace App\GraphQL\User\RegistrationMutation;

use App\Entity\User;
use App\Security\SystemRoles;
use Hasura\Bundle\GraphQLite\Attribute\ObjectAssertion;
use Hasura\Bundle\GraphQLite\Attribute\Transactional;
use Hasura\GraphQLiteBridge\Attribute\ArgNaming;
use Hasura\GraphQLiteBridge\Attribute\Roles;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use TheCodingMachine\GraphQLite\Annotations as GQL;

final class Resolver
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    #[GQL\Mutation(name: 'user_registration', outputType: 'user_registration_mutation_output!')]
    #[Roles(SystemRoles::ROLE_ANONYMOUS)]
    #[ArgNaming(for: 'inputObj', name: 'input_obj')]
    #[ObjectAssertion(for: 'inputObj')]
    #[Transactional]
    public function __invoke(
        Input $inputObj
    ): User {
        $user = new User();
        $user->setName($inputObj->name);
        $user->setEmail($inputObj->email);
        $user->setPassword($this->hasher->hashPassword($user, $inputObj->password));

        return $user;
    }
}
```

và [role hierarchy](https://symfony.com/doc/current/security.html#hierarchical-roles) như sau:

```yaml
role_hierarchy:
    ROLE_ADMIN:       ROLE_USER
    ROLE_SUPER_ADMIN: [ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]
```

Để persist các application state trên lên Hasura metadata thì bạn cần thực thi Symfony console command sau:

```shell
php bin/console hasura:metadata:persist-state
```

Sau khi persist xong ngay bây giờ bạn hãy thử kiểm tra lại application [remote schema permissions](https://hasura.io/docs/latest/graphql/core/remote-schemas/auth/remote-schema-permissions.html) trên Hasura sẽ thấy kết quả sau:

![remote schema permissions](../assets/remote-schema-permissions.png)

Và tiếp đến hãy thử kiểm tra [inherited roles](https://hasura.io/docs/latest/graphql/core/auth/authorization/inherited-roles.html) trên Hasura sẽ thấy kết quả sau:

![inherited roles](../assets/inherited-roles.png)

Vậy là bạn đã hoàn tất việc persist application state lên Hasura rồi đấy.