<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\GraphQLite\Parameter;

use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ResolveInfo;
use Hasura\GraphQLiteBridge\Parameter\WrappingParameterInterface;
use Hasura\GraphQLiteBridge\Parameter\WrappingParameterTrait;
use Illuminate\Contracts\Validation\Factory;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Exceptions\GraphQLAggregateException;
use TheCodingMachine\GraphQLite\Laravel\Exceptions\ValidateException;
use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;

final class ObjectAssertion implements InputTypeParameterInterface, WrappingParameterInterface
{
    use WrappingParameterTrait;

    public function __construct(
        private Factory $factory,
        private string $atPath,
        private ?array $customErrorArgumentNames,
        InputTypeParameterInterface $parameterMapped
    ) {
        $this->parameter = $parameterMapped;
    }

    public function resolve(?object $source, array $args, $context, ResolveInfo $info)
    {
        $object = $this->parameter->resolve($source, $args, $context, $info);

        if (!method_exists($object, 'rules')) {
            return $object;
        }

        $normalizer = new ObjectNormalizer();
        $data = $normalizer->normalize($object);

        $customMessages = method_exists($object, 'customMessages') ? $object->customMessages() : null;
        $customAttributes = method_exists($object, 'customAttributes') ? $object->customAttributes() : null;

        $validator = $this->factory->make($data, $object->rules(), $customMessages, $customAttributes);

        if ($validator->fails()) {
            $errorMessages = [];

            foreach ($validator->errors()->toArray() as $field => $errors) {
                foreach ($errors as $error) {
                    $errorMessages[] = ValidateException::create($error, $this->getErrorArgumentName($object, $field));
                }
            }

            GraphQLAggregateException::throwExceptions($errorMessages);
        }

        return $object;
    }

    private function getErrorArgumentName(object $object, string $field): string
    {
        $fieldParts = explode('.', $field);

        foreach ($fieldParts as &$fieldPart) {
            try {
                $ref = new \ReflectionProperty($object, $fieldPart);

                $fieldAttributes = $ref->getAttributes(Field::class);

                if (empty($fieldAttributes)) {
                    break;
                }

                $fieldAttribute = $fieldAttributes[0]->newInstance();

                $fieldPart = $fieldAttribute->getName() ?? $fieldPart;

                $ref->setAccessible(true);

                $object = $ref->getValue($object);

                if (!is_object($object)) {
                    break;
                }
            } catch (\ReflectionException) {
                break;
            }
        }

        $argumentName = sprintf('%s.%s', $this->atPath, implode('.', $fieldParts));

        return $this->customErrorArgumentNames[$argumentName] ?? $argumentName;
    }

    public function getType(): InputType
    {
        return $this->parameter->getType();
    }

    public function hasDefaultValue(): bool
    {
        return $this->parameter->hasDefaultValue();
    }

    public function getDefaultValue()
    {
        return $this->parameter->getDefaultValue();
    }
}