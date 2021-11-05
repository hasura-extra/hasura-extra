<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests\Fixture;

use Symfony\Component\Uid\Uuid;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Annotations\UseInputType;

#[Type(name: 'scalar_output', default: true)]
final class ScalarTypeQuery
{
    #[Field(name: 'date', outputType: 'date')]
    public ?\DateTimeInterface $date;

    #[Field(name: 'json', outputType: 'json')]
    public ?array $json;

    #[Field(name: 'jsonb', outputType: 'jsonb')]
    public ?array $jsonb;

    #[Field(name: 'timestamptz', outputType: 'timestamptz')]
    public ?\DateTimeInterface $timestamptz;

    #[Field(name: 'timetz', outputType: 'timetz')]
    public ?\DateTimeInterface $timetz;

    #[Field(name: 'uuid')]
    public ?Uuid $uuid;

    #[Query(name: 'scalar', outputType: 'scalar_output')]
    public function __invoke(
        #[UseInputType(inputType: 'date')] ?\DateTimeInterface $date,
        #[UseInputType(inputType: 'json')] ?array $json,
        #[UseInputType(inputType: 'jsonb')] ?array $jsonb,
        #[UseInputType(inputType: 'timestamptz')] ?\DateTimeInterface $timestamptz,
        #[UseInputType(inputType: 'timetz')] ?\DateTimeInterface $timetz,
        ?Uuid $uuid,
    ): self {
        $this->date = $date;
        $this->json = $json;
        $this->jsonb = $jsonb;
        $this->timestamptz = $timestamptz;
        $this->timetz = $timetz;
        $this->uuid = $uuid;

        return $this;
    }
}