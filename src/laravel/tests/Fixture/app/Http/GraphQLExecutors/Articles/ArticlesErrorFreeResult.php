<?php

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Fixture\App\Http\GraphQLExecutors\Articles;

class ArticlesErrorFreeResult extends \Spawnia\Sailor\ErrorFreeResult
{
    public Articles $data;
}
