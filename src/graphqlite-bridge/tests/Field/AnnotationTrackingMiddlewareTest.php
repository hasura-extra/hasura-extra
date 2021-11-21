<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests\Field;

use Hasura\GraphQLiteBridge\Attribute\Roles;
use Hasura\GraphQLiteBridge\Tests\TestCase;

final class AnnotationTrackingMiddlewareTest extends TestCase
{
    public function testTrack(): void
    {
        $this->schema->assertValid(); // trigger annotation tracking middleware.

        $this->assertNotEmpty($this->annotationTracker->getQueryFieldAnnotations(Roles::class));
        $this->assertNotEmpty($this->annotationTracker->getMutationFieldAnnotations(Roles::class));

        $this->assertArrayHasKey('dummy', $this->annotationTracker->getQueryFieldAnnotations(Roles::class));
        $this->assertArrayHasKey('dummy', $this->annotationTracker->getMutationFieldAnnotations(Roles::class));

        $queryAnnotations = $this->annotationTracker->getQueryFieldAnnotations(Roles::class, 'dummy');
        $this->assertCount(1, $queryAnnotations);
        $this->assertSame(
            ['A', 'B'],
            $queryAnnotations[0]->getNames()
        );

        $mutationAnnotations = $this->annotationTracker->getMutationFieldAnnotations(Roles::class, 'dummy');
        $this->assertCount(1, $mutationAnnotations);
        $this->assertSame(
            ['A', 'B', 'C'],
            $mutationAnnotations[0]->getNames()
        );
    }
}
