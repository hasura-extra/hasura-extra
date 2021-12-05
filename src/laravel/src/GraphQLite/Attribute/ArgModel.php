<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\GraphQLite\Attribute;

use TheCodingMachine\GraphQLite\Annotations\ParameterAnnotationInterface;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class ArgModel implements ParameterAnnotationInterface
{
    public function __construct(
        private string $for,
        private string $argName = 'id',
        private string $fieldName = 'id',
        private string $inputType = 'ID'
    ) {
        $this->for = ltrim($this->for, '$');
    }

    public function getInputType(): string
    {
        return $this->inputType;
    }

    public function getTarget(): string
    {
        return $this->for;
    }

    public function getArgName(): string
    {
        return $this->argName;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }
}
