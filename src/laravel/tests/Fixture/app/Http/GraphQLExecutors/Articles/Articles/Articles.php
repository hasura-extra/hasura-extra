<?php

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Fixture\App\Http\GraphQLExecutors\Articles\Articles;

class Articles extends \Spawnia\Sailor\TypedObject
{
    /** @var int */
    public $id;

    /** @var string */
    public $title;

    public function idTypeMapper(): callable
    {
        return new \Spawnia\Sailor\Mapper\DirectMapper();
    }

    public function titleTypeMapper(): callable
    {
        return new \Spawnia\Sailor\Mapper\DirectMapper();
    }
}
