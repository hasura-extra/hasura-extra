---
id: graphqlite
title: GraphQLite
sidebar_title: GraphQLite
---

Hasura Extra tích hợp với GraphQLite để xây dựng GraphQL server handle business logic, nếu như bạn chưa biết về nó thì trước
hết nên đọc tài liệu tại [đây](https://graphqlite.thecodingmachine.io/).

## Attributes

Dưới đây là các attributes của GraphQLite mở rộng được Hasura Extra cung cấp

### ArgNaming

Đây là một attribute dùng cho việc custom name của field arg (mặc định field arg không thay đổi được và lấy theo PHP arg).


Ví dụ chúng ta có resolver sau:

```php
use TheCodingMachine\GraphQLite\Annotations as GQL;

class Resolver 
{
    #[GQL\Query(name: 'arg_naming')]
    public function __invoke(string $camelCase): string 
    {
        return 'hello';
    }
    
}
```

Khi thực thi query get field `arg_naming` sẽ như sau:

```GraphQL
query {
    arg_naming(camelCase: "test")
}
```

Và chúng ta sẽ thay đổi field arg `camelCase` sang `camel_case`:

```php
use Hasura\GraphQLiteBridge\Attribute\ArgNaming;
use TheCodingMachine\GraphQLite\Annotations as GQL;

class Resolver 
{
    #[GQL\Query(name: 'arg_naming')]
    #[ArgNaming(for: 'camelCase', name: 'camel_case')]
    public function __invoke(string $camelCase): string 
    {
        return 'hello';
    }
    
}
```

Câu query get field sẽ thành:

```GraphQL
query {
    arg_naming(camel_case: "test")
}
```

### ArgEntity

:::caution
Attribute này chỉ dành cho Symfony bundle
:::

Attribute này giúp bạn đơn giản hóa việc get Doctrine entity từ user input ví dụ:

```php
use App\Entity\MyEntity;
use Doctrine\ORM\EntityManagerInterface;
use TheCodingMachine\GraphQLite\Annotations as GQL;

class Resolver 
{
    public function __construct(private EntityManagerInterface $em)
    {
    }
    
    #[GQL\Query(name: 'arg_entity')]
    public function __invoke(int $id): string 
    {
        $entity = $this->em->getRepository(MyEntity::class)->find($id);
        
        return 'hello';
    }
    
}
```

Resolver trên sẽ được đơn giản hóa khi sử dụng attribute `ArgEntity`:

```php
use App\Entity\MyEntity;
use Hasura\Bundle\GraphQLite\Attribute\ArgEntity;
use TheCodingMachine\GraphQLite\Annotations as GQL;

class Resolver
{
    #[GQL\Query(name: 'arg_entity')]
    #[ArgEntity(for: 'entity')]
    public function __invoke(MyEntity $entity): string 
    {
        return 'hello';
    }
    
}
```

Mặc định `ArgEntity` sẽ sử dụng `id` là tên của arg, graphql input type là `ID`, search trên column name là `id` và Doctrine manager là `null` (default), trong trường hợp bạn muốn custom lại thì bạn có thể truyền thêm thông số:

```php
use App\Entity\Article;
use Hasura\Bundle\GraphQLite\Attribute\ArgEntity;
use TheCodingMachine\GraphQLite\Annotations as GQL;

class Resolver
{
    #[GQL\Query(name: 'arg_entity')]
    #[ArgEntity(for: 'article', argName: 'article_id', inputType: 'Int!', entityManager: 'custom')]
    public function __invoke(Article $article): string 
    {
        return 'hello';
    }
    
}
```

### ObjectAssertion

:::caution
Attribute này chỉ dành cho Symfony bundle
:::

Attribute này giúp bạn validate object input

Giả sử như chúng ta có Input object sau:

```php
use TheCodingMachine\GraphQLite\Annotations as GQL;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input]
class Input 
{
    #[GQL\Field]
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;
}
```

Và resolver sử dụng input object trên:

```php
use Hasura\Bundle\GraphQLite\Attribute\ObjectAssertion;
use TheCodingMachine\GraphQLite\Annotations as GQL;

class Resolver
{
    #[GQL\Mutation(name: 'object_assertion')]
    #[ObjectAssertion(for: 'input')]
    public function __invoke(Input $input): bool 
    {
        return true;
    }
    
}
```

Khi query mutation field `object_assertion` mà trả về giá trị `true` đồng nghĩa với property `email` của input object
là chuỗi email không rỗng, ngược lại sẽ báo lỗi.

Mặc định `ObjectAssertion` sẽ thực thi validate trước khi resolver được gọi, bạn có thể bật mode
sau khi resolver được gọi và sử dụng kết hợp với `ArgEntity` để validate thêm 1 lần nữa. Ví dụ ta có
entity `User` như sau:

```php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity]
#[UniqueEntity(fields: 'email')]
class User 
{
    #[ORM\Column(unique: true)]
    private $email;
    
    public function setEmail(string $email): void 
    {
        $this->email = $email;
    }
}
```

và mutation resolver `update_user` field như sau:

```php
use Hasura\Bundle\GraphQLite\Attribute\ArgEntity;
use Hasura\Bundle\GraphQLite\Attribute\ObjectAssertion;
use Hasura\Bundle\GraphQLite\Attribute\Transactional;
use TheCodingMachine\GraphQLite\Annotations as GQL;

class Resolver
{
    #[GQL\Mutation(name: 'update_user')]
    #[ArgEntity(for: 'user')]
    #[ObjectAssertion(for: 'input')]
    #[ObjectAssertion(for: 'user', mode: ObjectAssertion::AFTER_RESOLVE_CALL)]
    #[Transactional]
    public function __invoke(Input $input, User $user): bool 
    {
        $user->setEmail($input->email);
        
        return true;
    }
    
}
```

Khi mutation field `update_user` được gọi property `email` của object input đã được validate và chúng ta sử dụng property đó để set email cho entity
User, tiếp đến resolver sẽ trả về `true` và đồng thời entity User sẽ được validate nếu như field `email` không tồn tại trên DB thì
entity sẽ được update và flush vào DB nhờ `Transactional` attribute xem thêm tại bên dưới, ngược lại sẽ báo lỗi `email` không unique.

### Transactional

:::caution
Attribute này chỉ dành cho Symfony bundle
:::

Attribute này sẽ wrap resolver của bạn trong một Doctrine transaction nếu như có bất kỳ exception nào xảy ra trong resolver của bạn thì toàn bộ SQL query
sẽ được rollback, ngược lại nếu không các entities đang managed bởi Entity Manager sẽ được flush vào database. Ngoài ra attribute này còn có tính năng
tự động persist đối với các entity vừa được khởi tạo (không get từ repository), ví dụ:

```php
use Hasura\Bundle\GraphQLite\Attribute\ObjectAssertion;
use Hasura\Bundle\GraphQLite\Attribute\Transactional;
use TheCodingMachine\GraphQLite\Annotations as GQL;

class Resolver
{
    #[GQL\Mutation(name: 'insert_user')]
    #[ObjectAssertion(for: 'input')]
    #[Transactional]
    public function __invoke(Input $input): User 
    {
        $user = new User();
        $user->setEmail($input->email);
        
        return $user;
    }
    
}
```

Khi mutation field `insert_user` được gọi thì object User sẽ được khởi tạo thông qua input object của end-user
và `Transactional` attribute sẽ tự động persist nó giúp bạn, sau đó sẽ flush vào database.

Nếu project của bạn có nhiều database connections thì bạn có thể chỉ định Entity Manager thông qua thông số `entityManager` như sau:

```php 
#[Transactional(entityManager: 'custom')]
```

### Roles

Attribute này dùng cho authorization khác với [Right](https://graphqlite.thecodingmachine.io/docs/authentication-authorization#logged-and-right-annotations) attribute,
nó hổ trợ bạn thêm được nhiều roles cùng 1 lúc và hổ trợ [persist-state]() sync roles lên remote schema permissions.

```php
use Hasura\GraphQLiteBridge\Attribute\Roles;
use TheCodingMachine\GraphQLite\Annotations as GQL;

class Resolver
{
    #[GQL\Mutation(name: 'hello')]
    #[Roles('ROLE_USER', 'ROLE_ADMIN')]
    public function __invoke(Input $input): string 
    {
        return 'world';
    }
}
```

## Scalar types

Hasura Extra cung cấp [custom scalar types](https://graphqlite.thecodingmachine.io/docs/custom-types#registering-a-custom-scalar-type-advanced) compatible với Hasura giúp cho việc tạo
[data federation](https://hasura.io/docs/latest/graphql/core/databases/postgres/schema/remote-relationships/remote-schema-relationships.html)
thuận tiện hơn:

+ json
+ jsonb
+ timestamptz
+ timetz
+ date
+ uuid

Ngoài ra bạn còn có thể ứng dụng các type trên để validate user input format.

Cách sử dụng:

```php
#[Query(name: 'test_scalar', outputType: 'json')]
public function __invoke(
    #[UseInputType(inputType: 'date')] ?\DateTimeInterface $date = null,
    #[UseInputType(inputType: 'json')] ?array $json = null,
    #[UseInputType(inputType: 'jsonb')] ?array $jsonb = null,
    #[UseInputType(inputType: 'timestamptz')] ?\DateTimeInterface $timestamptz = null,
    #[UseInputType(inputType: 'timetz')] ?\DateTimeInterface $timetz = null,
    ?Uuid $uuid = null,
): array {
    return compact(
        'date', 
        'json', 
        'jsonb', 
        'timestamptz',
        'timetz',
        'uuid'
    );
}
```