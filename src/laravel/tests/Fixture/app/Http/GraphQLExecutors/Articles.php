<?php

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Fixture\App\Http\GraphQLExecutors;

class Articles extends \Spawnia\Sailor\Operation
{
    public static function execute(): Articles\ArticlesResult
    {
        return self::executeOperation(...func_get_args());
    }

    public static function document(): string
    {
        return /* @lang GraphQL */ 'query Articles {
          articles: _flattenmany_article {
            id
            title
          }
        }';
    }

    public static function endpoint(): string
    {
        return 'hasura';
    }
}
