<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\GraphQLite\Attribute;

use Hasura\Bundle\GraphQLite\Attribute\Transactional;
use PHPUnit\Framework\TestCase;

final class TransactionalTest extends TestCase
{
    public function testConstructor(): void
    {
        $attribute = new Transactional(false, '1');

        $this->assertFalse($attribute->isAutoPersist());
        $this->assertSame('1', $attribute->getEntityManager());
    }
}
