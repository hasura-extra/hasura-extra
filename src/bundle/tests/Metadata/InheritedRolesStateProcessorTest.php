<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Metadata;

use Hasura\Bundle\Metadata\ChildRoleMissingException;
use Hasura\Bundle\Metadata\InheritedRolesStateProcessor;
use Hasura\Metadata\Manager;
use Hasura\Metadata\OperatorInterface;
use Hasura\Metadata\RemoteSchema;

final class InheritedRolesStateProcessorTest extends TestCase
{
    public function testProcess(): void
    {
        $data = $this->client->metadata()->query('export_metadata', [], 2);
        $roles = array_column($data['metadata']['inherited_roles'], 'role_name');

        $this->assertNotContains('ROLE_ADMIN', $roles);

        $processor = new InheritedRolesStateProcessor(
            self::getContainer()->getParameter('security.role_hierarchy.roles'),
            new RemoteSchema('bundle')
        );

        $manager = new Manager($this->client, '', $this->createMock(OperatorInterface::class));
        $processor->process($manager);

        $data = $this->client->metadata()->query('export_metadata', [], 2);

        $roles = array_column($data['metadata']['inherited_roles'], null, 'role_name');

        $this->assertArrayHasKey('ROLE_ADMIN', $roles);
        $this->assertSame(['ROLE_USER', 'ROLE_TESTER'], $roles['ROLE_ADMIN']['role_set']);
    }

    public function testProcessChildRoleMissing(): void
    {
        $this->expectException(ChildRoleMissingException::class);

        $manager = new Manager($this->client, '', $this->createMock(OperatorInterface::class));
        $processor = new InheritedRolesStateProcessor(
            self::getContainer()->getParameter('security.role_hierarchy.roles'),
            null
        );

        $processor->process($manager);
    }
}