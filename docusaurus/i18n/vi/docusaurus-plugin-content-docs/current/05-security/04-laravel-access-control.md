---
id: laravel-access-control
title: Laravel access control (authorization)
sidebar_label: Laravel access control
---

Hasura extra package cung cấp sẵn cho bạn authorization system thông qua role access control, khái niệm của nó giống với Hasura,
mỗi request access vào system của bạn đều phải có role kể cả [unauthenticated request cũng có role](./03-unauthenticated-role.mdx).

## Định nghĩa roles

Để định nghĩa roles của user bạn hãy tạo method `getRoles` trong Eloquent model `App\Models\User`:

```php title="app/Models/User.php"
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /*
     * List roles of user
     */
    public function getRoles(): array
    {
        return ['user'];
    }
    
    ...
}
```

Method này có nhiệm vụ cung cấp set roles của user cho hệ thống, theo ví dụ trên roles fixed là `['user']` 
bạn có thể lưu roles trong column json hoặc tạo liên kết đến table roles tùy theo business logic của project của bạn, ví dụ 
lưu khi roles trên `json` column:

```php title="app/Models/User.php"
/*
 * List roles of user
 */
public function getRoles(): array
{
    return $this->roles;
}
```

Sau khi định nghĩa xong bạn có thể xài các [authorization actions thông qua Gate](https://laravel.com/docs/8.x/authorization#authorizing-actions-via-gates) 
để check roles của user đang đăng nhập:

```php
use Illuminate\Support\Facades\Gate;

Gate::allows('admin');
Gate::allows('manager');
Gate::allows('user');
```

Ngoài ra role access system còn integrate với [Roles attribute của GraphQLite](../03-handle-business-logic/02-graphql-server/02-attributes.md#attributes-roles) để
bạn có thể dễ dàng check authorization của GraphQL query/mutation fields.

## Inherited roles {#inherited-roles}

Bạn có thể định nghĩa inherited roles giống với khái niệm [inherited roles của Hasura](https://hasura.io/docs/latest/graphql/core/auth/authorization/inherited-roles.html)
tại `auth.inherited_roles` trong file `config/hasura.php`.

Ví dụ ta thiết lập `inherited_roles` như sau:

```php title="config/hasura.php"
'inherited_roles' => [
    'admin' => ['manager', 'user'],
],
```

Theo ví dụ trên, nếu user có role `admin` thì khi sử dụng `Gate::allows('manager')` hoặc `Gate::allows('user')` đều trả về `true` vì role admin
được kế thừa từ role `manager` và `user`.

Roles inherited còn được hổ trợ [persist state](../07-manage-metadata/07-persist-application-state.mdx) lên Hasura.

## Disable access control

Nếu như bạn không muốn Hasura Extra can thiệp vào authorization system của project của bạn thì bạn có thể tắt toàn bộ tính năng mô tả ở trên
thông qua option `auth.enabled_role_check_method` trong file `config/hasura.php`.

:::warning
Khi bạn tắt role access control system thì bạn cần implements lại logic check role, nếu không hệ thống của bạn sẽ KHÔNG bảo mật.
:::