<?php

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Fixture\App\Http\GraphQLExecutors\Articles;

class ArticlesResult extends \Spawnia\Sailor\Result
{
    public ?Articles $data;

    protected function setData(\stdClass $data): void
    {
        $this->data = Articles::fromStdClass($data);
    }

    public function errorFree(): ArticlesErrorFreeResult
    {
        return ArticlesErrorFreeResult::fromResult($this);
    }
}
