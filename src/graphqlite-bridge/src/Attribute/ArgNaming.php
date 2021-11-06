<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Attribute;

use TheCodingMachine\GraphQLite\Annotations\ParameterAnnotationInterface;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class ArgNaming implements ParameterAnnotationInterface
{
    public function __construct(private string $for, private string $name)
    {
        $this->for = ltrim($this->for, '$');
    }

    public function getTarget(): string
    {
        return $this->for;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
