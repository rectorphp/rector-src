<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Reflection;

use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\PassedByReference;
use PHPStan\Type\ConstantTypeHelper;
use PHPStan\Type\Type;
use Rector\Core\Exception\NotImplementedYetException;
use ReflectionFunction;
use ReflectionParameter;
use Throwable;

final class SimplePhpParameterReflection implements ParameterReflection
{
    private readonly ReflectionParameter $parameter;

    public function __construct(ReflectionFunction $reflectionFunction, int $position)
    {
        $this->parameter = $reflectionFunction->getParameters()[$position];
    }

    public function getName(): string
    {
        return $this->parameter->getName();
    }

    public function isOptional(): bool
    {
        return $this->parameter->isOptional();
    }

    /**
     * getType() is never used yet
     */
    public function getType(): Type
    {
        throw new NotImplementedYetException();
    }

    public function passedByReference(): PassedByReference
    {
        return $this->parameter->isPassedByReference()
            ? PassedByReference::createCreatesNewVariable()
            : PassedByReference::createNo();
    }

    public function isVariadic(): bool
    {
        return $this->parameter->isVariadic();
    }

    public function getDefaultValue(): ?Type
    {
        try {
            if ($this->parameter->isDefaultValueAvailable()) {
                $defaultValue = $this->parameter->getDefaultValue();
                return ConstantTypeHelper::getTypeFromValue($defaultValue);
            }
        } catch (Throwable) {
            return null;
        }

        return null;
    }
}
