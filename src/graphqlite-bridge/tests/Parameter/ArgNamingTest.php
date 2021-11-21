<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests\Parameter;

use GraphQL\Type\Definition\ResolveInfo;
use Hasura\GraphQLiteBridge\Parameter\ArgNaming;
use Hasura\GraphQLiteBridge\Tests\TestCase;
use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;

final class ArgNamingTest extends TestCase
{
    public function testConstructor(): void
    {
        $wrappedInput = $this->createMock(InputTypeParameterInterface::class);
        $argNaming = new ArgNaming('a', 'b', $wrappedInput);

        $this->assertSame('a', $argNaming->getName());
        $this->assertSame('b', $argNaming->getArgName());
        $this->assertSame($wrappedInput, $argNaming->getWrappedParameter());
    }

    public function testDecoratingMethods(): void
    {
        $wrappedInput = $this->createMock(InputTypeParameterInterface::class);
        $argNaming = new ArgNaming('a', 'b', $wrappedInput);

        $wrappedInput->expects($this->once())->method('getType');
        $wrappedInput->expects($this->once())->method('hasDefaultValue');
        $wrappedInput->expects($this->once())->method('getDefaultValue');
        $wrappedInput->expects($this->once())->method('resolve');

        $argNaming->getType();
        $argNaming->hasDefaultValue();
        $argNaming->getDefaultValue();
        $argNaming->resolve(null, [], null, $this->createMock(ResolveInfo::class));
    }
}
