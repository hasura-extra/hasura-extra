<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Fixture\App\Http\GraphQL\Query;

use Symfony\Component\Uid\Uuid;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\UseInputType;

final class RootTypeMapperTest
{
    #[Query(name: 'root_type_mapper_test', outputType: 'json')]
    public function __invoke(
        #[UseInputType(inputType: 'json')] array $json,
        #[UseInputType(inputType: 'jsonb')] array $jsonb,
        #[UseInputType(inputType: 'date')] \DateTimeInterface $date,
        #[UseInputType(inputType: 'timestamptz')] \DateTimeInterface $timestamptz,
        #[UseInputType(inputType: 'timetz')] \DateTimeInterface $timetz,
        #[UseInputType(inputType: 'uuid')] Uuid $uuid,
    ): array {
        return [
            'json' => $json,
            'jsonb' => $jsonb,
            'date' => $date,
            'timestamptz' => $timestamptz,
            'timetz' => $timetz,
            'uuid' => $uuid,
        ];
    }
}
