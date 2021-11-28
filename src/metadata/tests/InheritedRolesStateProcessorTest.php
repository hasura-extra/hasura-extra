<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Tests;

use Hasura\Metadata\ChildRoleMissingException;
use Hasura\Metadata\InheritedRolesStateProcessor;
use Hasura\Metadata\RemoteSchema;

final class InheritedRolesStateProcessorTest extends TestCase
{
    public function testProcess(): void
    {
        $data = $this->client->metadata()->query('export_metadata', [], 2);
        $roles = array_column($data['metadata']['inherited_roles'], 'role_name');

        $this->assertNotContains('a', $roles);
        $this->assertNotContains('d', $roles);

        $processor = new InheritedRolesStateProcessor(
            [
                'a' => ['b', 'c'],
                'd' => ['e'],
            ],
            new RemoteSchema('metadata'),
            'schema { query: Query } type Query { dummy: String! }'
        );

        $processor->process($this->manager);

        $data = $this->client->metadata()->query('export_metadata', [], 2);

        $roles = array_column($data['metadata']['inherited_roles'], null, 'role_name');

        $this->assertArrayHasKey('a', $roles);
        $this->assertArrayHasKey('d', $roles);
        $this->assertSame(['b', 'c'], $roles['a']['role_set']);
        $this->assertSame(['e'], $roles['d']['role_set']);
    }

    public function testProcessChildRoleMissing(): void
    {
        $this->expectException(ChildRoleMissingException::class);

        $processor = new InheritedRolesStateProcessor(
            [
                'a' => ['b', 'c'],
            ],
            null,
            null
        );

        $processor->process($this->manager);
    }
}
