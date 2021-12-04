---
id: graphqlite-attributes
title: Attributes
sidebar_label: Attributes
---

Hasura Extra cung cấp cho bạn set [GraphQLite](https://graphqlite.thecodingmachine.io/) attributes để có thể xây dựng GraphQL server đơn giản hơn.

## Common attributes

Tập hợp các attributes có thể sử dụng ở cả Laravel và Symfony frameworks.

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


### Roles {#attributes-roles}

Attribute này dùng cho authorization khác với [Right](https://graphqlite.thecodingmachine.io/docs/authentication-authorization#logged-and-right-annotations) attribute,
nó hổ trợ bạn thêm được nhiều roles cùng 1 lúc và hổ trợ [persist-state](../../07-manage-metadata/07-persist-application-state.mdx) sync roles lên remote schema permissions.

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

## Laravel attributes 

Tập hợp các attributes sử dụng khi làm việc với Laravel framework.

### ArgModel

Attribute này giúp bạn đơn giản hóa việc get Eloquent model từ user input ví dụ:

```php
use App\Models\MyModel;
use Doctrine\ORM\EntityManagerInterface;
use TheCodingMachine\GraphQLite\Annotations as GQL;

class Resolver 
{
    #[GQL\Query(name: 'arg_model')]
    public function __invoke(int $id): string 
    {
        $entity = MyModel::find($id);
        
        return 'hello';
    }
    
}
```

Resolver trên sẽ được đơn giản hóa khi sử dụng attribute `ArgModel`:

```php
use App\Models\MyModel;
use Hasura\Laravel\GraphQLite\Attribute\ArgModel;
use TheCodingMachine\GraphQLite\Annotations as GQL;

class Resolver
{
    #[GQL\Query(name: 'arg_model')]
    #[ArgModel(for: 'model')]
    public function __invoke(MyModel $model): string 
    {
        return 'hello';
    }
    
}
```

Mặc định `ArgModel` sẽ sử dụng `id` là tên của arg, graphql input type là `ID!`, search trên column name là `id`, trong trường hợp bạn muốn custom lại thì bạn có thể truyền thêm thông số:

```php
use App\Models\Article;
use Hasura\Laravel\GraphQLite\Attribute\ArgModel;
use TheCodingMachine\GraphQLite\Annotations as GQL;

class Resolver
{
    #[GQL\Query(name: 'arg_model')]
    #[ArgModel(for: 'article', argName: 'article_id', inputType: 'Int!')]
    public function __invoke(Article $article): string 
    {
        return 'hello';
    }
    
}
```

Nếu như không tìm thấy Eloquent model theo `id` user input, mặc định hệ thống sẽ văng lỗi không tìm thấy đến người dùng nếu như bạn chấp nhận
việc không tìm thấy model để upsert entity thì hãy cho phép arg `null`:

```php
#[ArgModel(for: 'article', argName: 'article_id', inputType: 'Int!')]
public function __invoke(?Article $article): string 
```

### ValidateObject

Attribute này giúp bạn validate object input

Giả sử như chúng ta có Input object sau:

```php
use TheCodingMachine\GraphQLite\Annotations as GQL;

#[GQL\Input]
class Input 
{
    #[GQL\Field]
    public string $email;
    
    public function rules(): array 
    {
        return ['email' => 'required|email'];
    }
}
```

Với `rules` method trong input object sẽ là nơi định nghĩa các [validation rules](https://laravel.com/docs/8.x/validation#available-validation-rules),
object property names sẽ tương ứng với field name.

Và resolver sử dụng input object trên:

```php
use Hasura\Laravel\GraphQLite\Attribute\ValidateObject;
use TheCodingMachine\GraphQLite\Annotations as GQL;

class Resolver
{
    #[GQL\Mutation(name: 'object_assertion')]
    #[ValidateObject(for: 'input')]
    public function __invoke(Input $input): bool 
    {
        return true;
    }
    
}
```

Khi query mutation field `object_assertion` mà trả về giá trị `true` đồng nghĩa với property `email` của input object
là chuỗi email không rỗng, ngược lại sẽ báo lỗi.

Ngoài ra bạn có thể define thêm methods `customMessages` và `customAttributes` cho input class để 
[điểu chỉnh câu báo lỗi hoặc attribute](https://laravel.com/docs/master/validation#manual-customizing-the-error-messages) cho
phù hợp:

```php
use TheCodingMachine\GraphQLite\Annotations as GQL;

#[GQL\Input]
class Input 
{
    #[GQL\Field]
    public string $email;
    
    public function rules(): array 
    {
        return ['email' => 'required|email'];
    }
    
    public function customMessages(): array
    {
        return ['email.required' => 'You should be type your :attribute.'];
    }
    
    public function customAttributes(): array 
    {
        return ['email' => 'email address'];
    }
}
```

### Transactional

Attribute này sẽ wrap resolver của bạn trong một database transaction, nếu như có bất kỳ exception nào xảy ra trong resolver của bạn thì toàn bộ SQL query
đã thực thi trước đó sẽ được rollback, ví dụ:

```php
use App\Models\Article;
use Hasura\Laravel\GraphQLite\Attribute\ValidateObject;
use Hasura\Laravel\GraphQLite\Attribute\Transactional;
use TheCodingMachine\GraphQLite\Annotations as GQL;

class Resolver
{
    #[GQL\Mutation(name: 'insert_user')]
    #[ValidateObject(for: 'input')]
    #[Transactional]
    public function __invoke(Input $input): bool 
    {
        $article = new Article();
        $article->title = 'Hello World';
        $article->saveOrFail();
        
        throw new \RuntimeException('Test rollback');
        
        return true;
    }
    
}
```

Như ví dụ trên `\RuntimeException` sẽ được throw và article `Hello World` sẽ không bị save lại.

Nếu project của bạn có nhiều database connections thì bạn có thể chỉ định connection name thông qua thông số `connection` như sau:

```php 
#[Transactional(connection: 'postgres2')]
```

## Symfony attributes

Tập hợp các attributes sử dụng khi làm việc với Symfony framework.

### ArgEntity

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

Mặc định `ArgEntity` sẽ sử dụng `id` là tên của arg, graphql input type là `ID!`, search trên column name là `id` và Doctrine manager là `null` (default), trong trường hợp bạn muốn custom lại thì bạn có thể truyền thêm thông số:

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

Nếu như không tìm thấy entity theo `id` user input, mặc định hệ thống sẽ văng lỗi không tìm thấy đến người dùng nếu như bạn chấp nhận
việc không tìm thấy entity để upsert entity thì hãy cho phép arg `null`:

```php
#[ArgEntity(for: 'article', argName: 'article_id', inputType: 'Int!', entityManager: 'custom')]
public function __invoke(?Article $article): string 
```

### ObjectAssertion

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

Attribute này sẽ wrap resolver của bạn trong một database transaction, nếu như có bất kỳ exception nào xảy ra trong resolver của bạn thì toàn bộ SQL query
đã thực thi trước đó sẽ được rollback, ví dụ:

```php
use Doctrine\ORM\EntityManagerInterface;
use Hasura\Bundle\GraphQLite\Attribute\ObjectAssertion;
use Hasura\Bundle\GraphQLite\Attribute\Transactional;
use TheCodingMachine\GraphQLite\Annotations as GQL;

class Resolver
{
    public function __construct(private EntityManagerInterface $em)
    {
    }
    
    #[GQL\Mutation(name: 'insert_user')]
    #[ObjectAssertion(for: 'input')]
    #[Transactional]
    public function __invoke(Input $input): User 
    {
        $user = new User();
        $user->setEmail($input->email);
        
        $this->em->persist($user);
        $this->em->flush();
        
        throw new \RuntimeException('Test rollback');
        
        return $user;
    }
    
}
```

Như ví dụ trên exception `\RuntimeException` sẽ được throw và entity user sẽ không bị flush vào database.

Ngoài ra attribute này còn có tính năng tự động persist đối với các entity vừa được khởi tạo (state new) giúp cho bạn không cần inject
entity manager dependency, ví dụ:

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

Nếu project của bạn có nhiều entity managers (multi database) thì bạn có thể chỉ định Entity Manager thông qua thông số `entityManager` như sau:

```php 
#[Transactional(entityManager: 'custom')]
```
