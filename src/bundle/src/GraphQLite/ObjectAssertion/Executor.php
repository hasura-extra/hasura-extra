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
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
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

        $this->ensureViolationPropertyPaths($value, $violations, $atPath);

        if (null !== $customViolationPropertyPaths) {
            $this->customViolationPropertyPaths($violations, $customViolationPropertyPaths);
        }

        throw new ValidationFailedException($violations);
    }

    private function customViolationPropertyPaths(
        ConstraintViolationListInterface $violations,
        array $customViolationPropertyPaths
    ): void {
        foreach ($violations as $pos => $violation) {
            /** @var ConstraintViolationInterface $violation */
            $propertyPath = $violation->getPropertyPath();

            if (isset($customViolationPropertyPaths[$propertyPath])) {
                $violation = $this->remapViolationPropertyPath(
                    $violation,
                    $customViolationPropertyPaths[$propertyPath]
                );

                $violations->set($pos, $violation);
            }
        }
    }

    private function ensureViolationPropertyPaths(
        object $value,
        ConstraintViolationListInterface $violations,
        string $atPath
    ): void {
        $refClass = new \ReflectionClass($value);

        foreach ($violations as $pos => $violation) {
            $violation = $this->ensureViolationPropertyPath($value, $refClass, $violation);
            $violation = $this->remapViolationPropertyPath(
                $violation,
                sprintf(
                    '%s.%s',
                    $atPath,
                    $violation->getPropertyPath()
                )
            );

            $violations->set($pos, $violation);
        }
    }

    private function ensureViolationPropertyPath(
        object $instance,
        \ReflectionClass $refClass,
        ConstraintViolation $violation,
    ): ConstraintViolation {
        $parts = explode('.', $violation->getPropertyPath());

        foreach ($parts as &$part) {
            try {
                $refProperty = $refClass->getProperty($part);
            } catch (\ReflectionException) {
                break;
            }

            $fieldAttributes = $refProperty->getAttributes(Field::class);

            if (empty($fieldAttributes)) {
                break;
            }

            $part = end($fieldAttributes)->newInstance()->getName() ?? $part;

            $refProperty->setAccessible(true);
            $instance = $refProperty->getValue($instance);

            if (is_object($instance)) {
                $refClass = new \ReflectionClass($instance);
            } else {
                break;
            }
        }

        return $this->remapViolationPropertyPath($violation, implode('.', $parts));
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
