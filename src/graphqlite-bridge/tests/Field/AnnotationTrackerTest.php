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

final class AnnotationTrackerTest extends TestCase
{
    public function testTrackQueryFieldAnnotation(): void
    {
        $roles = new Roles('a', 'b');
        $this->annotationTracker->trackQueryFieldAnnotation($roles, 'a');

        $this->assertArrayHasKey('a', $this->annotationTracker->getQueryFieldAnnotations(Roles::class));
        $this->assertContains($roles, $this->annotationTracker->getQueryFieldAnnotations(Roles::class, 'a'));
    }

    public function testTrackInvalidQueryFieldAnnotation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('~class should be class implements~');
        $this->annotationTracker->trackQueryFieldAnnotation(new \stdClass(), 'a');
    }

    public function testTrackMutationFieldAnnotation(): void
    {
        $roles = new Roles('a', 'b');
        $this->annotationTracker->trackMutationFieldAnnotation($roles, 'a');

        $this->assertArrayHasKey('a', $this->annotationTracker->getMutationFieldAnnotations(Roles::class));
        $this->assertContains($roles, $this->annotationTracker->getMutationFieldAnnotations(Roles::class, 'a'));
    }

    public function testTrackInvalidMutationFieldAnnotation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('~class should be class implements~');
        $this->annotationTracker->trackMutationFieldAnnotation(new \stdClass(), 'a');
    }
}