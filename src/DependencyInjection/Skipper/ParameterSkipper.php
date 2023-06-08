<?php

declare(strict_types=1);

namespace Rector\Core\DependencyInjection\Skipper;

use Rector\Core\DependencyInjection\TypeResolver\ParameterTypeResolver;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;
use Symfony\Component\DependencyInjection\Definition;

final class ParameterSkipper
{
    public function __construct(
        private readonly ParameterTypeResolver $parameterTypeResolver,
    ) {
    }

    public function shouldSkipParameter(
        ReflectionMethod $reflectionMethod,
        Definition $definition,
        ReflectionParameter $reflectionParameter
    ): bool {
        if (! $this->isArrayType($reflectionParameter)) {
            return true;
        }

        // already set
        $argumentName = '$' . $reflectionParameter->getName();
        if (isset($definition->getArguments()[$argumentName])) {
            return true;
        }

        $parameterType = $this->parameterTypeResolver->resolveParameterType(
            $reflectionParameter->getName(),
            $reflectionMethod
        );

        if ($parameterType === null) {
            return true;
        }

        // autowire only rector classes
        if (! str_starts_with($parameterType, 'Rector\\')) {
            return true;
        }

        // prevent circular dependency
        if ($definition->getClass() === null) {
            return false;
        }

        return is_a($definition->getClass(), $parameterType, true);
    }

    private function isArrayType(ReflectionParameter $reflectionParameter): bool
    {
        if (! $reflectionParameter->getType() instanceof ReflectionType) {
            return false;
        }

        $parameterReflectionType = $reflectionParameter->getType();
        if (! $parameterReflectionType instanceof ReflectionNamedType) {
            return false;
        }

        return $parameterReflectionType->getName() === 'array';
    }
}
