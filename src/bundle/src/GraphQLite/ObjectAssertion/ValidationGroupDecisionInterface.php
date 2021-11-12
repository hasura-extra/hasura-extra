<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\GraphQLite\ObjectAssertion;

use Symfony\Component\Validator\Constraints\GroupSequence;

/**
 * Implements classes will decide validation groups.
 */
interface ValidationGroupDecisionInterface
{
    /**
     * Groups will be use to validate.
     */
    public function getValidationGroups(): array|string|null|GroupSequence;
}