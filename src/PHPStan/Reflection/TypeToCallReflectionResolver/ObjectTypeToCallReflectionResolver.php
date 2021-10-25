<?php

declare(strict_types=1);

namespace Rector\Core\PHPStan\Reflection\TypeToCallReflectionResolver;

use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\Core\Contract\PHPStan\Reflection\TypeToCallReflectionResolver\TypeToCallReflectionResolverInterface;
use Rector\Core\ValueObject\MethodName;

/**
 * @see https://github.com/phpstan/phpstan-src/blob/b1fd47bda2a7a7d25091197b125c0adf82af6757/src/Type/ObjectType.php#L705
 */
final class ObjectTypeToCallReflectionResolver implements TypeToCallReflectionResolverInterface
{
    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function supports(Type $type): bool
    {
        return $type instanceof ObjectType;
    }

    /**
     * @param ObjectType $type
     */
    public function resolve(Type $type, Scope $scope): ?MethodReflection
    {
        $className = $type->getClassName();
        if (! $this->reflectionProvider->hasClass($className)) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($className);
        if (! $classReflection->hasNativeMethod(MethodName::INVOKE)) {
            return null;
        }

        return $classReflection->getNativeMethod(MethodName::INVOKE);
    }
}
