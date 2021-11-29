<?php

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Fixture\App\Http\GraphQLExecutors\Articles;

class Articles extends \Spawnia\Sailor\TypedObject
{
    /** @var array<int, \Hasura\Laravel\Tests\Fixture\App\Http\GraphQLExecutors\Articles\Articles\Articles> */
    public $articles;

    public function articlesTypeMapper(): callable
    {
        return static function (\stdClass $value): \Spawnia\Sailor\TypedObject {
            return \Hasura\Laravel\Tests\Fixture\App\Http\GraphQLExecutors\Articles\Articles\Articles::fromStdClass($value);
        };
    }
}
