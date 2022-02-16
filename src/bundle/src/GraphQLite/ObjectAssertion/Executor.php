<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\GraphQLite\ObjectAssertion;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Validator\ValidationFailedException;

final class Executor
{
    public function __construct(private ValidatorInterface $validator, private ContainerInterface $container)
    {
    }

    public function execute(
        ?object $value,
        string $atPath,
        array $customViolationPropertyPaths = null,
        array|string|null|GroupSequence $groups = null,
    ): void {
        if ($value instanceof ContainerAwareInterface) {
            $value->setContainer($this->container);
        }

        if (null === $groups && $value instanceof ValidationGroupDecisionInterface) {
            $groups = $value->getValidationGroups();
        }

        $violations = $this->validator->validate($value, groups: $groups);

        if (0 === count($violations)) {
            return;
        }

        foreach ($violations as $pos => $violation) {
            $violation = $this->ensureViolationPropertyPath($value, $violation, $atPath, $customViolationPropertyPaths);

            $violations->set($pos, $violation);
        }

        throw new ValidationFailedException($violations);
    }

    private function ensureViolationPropertyPath(
        object $instance,
        ConstraintViolation $violation,
        string $atPath,
        ?array $customViolationPropertyPaths
    ): ConstraintViolation {
        $parts = explode('.', $violation->getPropertyPath());

        foreach ($parts as &$part) {
            try {
                $refProperty = new \ReflectionProperty($instance, $part);
            } catch (\ReflectionException) {
                break;
            }

            $fieldAttributes = $refProperty->getAttributes(Field::class);

            if (empty($fieldAttributes)) {
                break;
            }

            $part = end($fieldAttributes)->newInstance()->getName() ?? $part;

            $refProperty->setAccessible(true);

            $instance = isset($instance->{$part}) ? $refProperty->getValue($instance) : null ;

            if (!is_object($instance)) {
                break;
            }
        }

        $path = sprintf('%s.%s', $atPath, implode('.', $parts));

        return $this->remapViolationPropertyPath($violation, $customViolationPropertyPaths[$path] ?? $path);
    }

    private function remapViolationPropertyPath(ConstraintViolation $violation, string $path): ConstraintViolation
    {
        return new ConstraintViolation(
            $violation->getMessage(),
            $violation->getMessageTemplate(),
            $violation->getParameters(),
            $violation->getRoot(),
            $path,
            $violation->getInvalidValue(),
            $violation->getPlural(),
            $violation->getCode()
        );
    }
}
