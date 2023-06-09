<?php

declare(strict_types=1);

namespace Rector\Core\PHPStan\Reflection\TypeToCallReflectionResolver;

use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Type;
use Rector\Core\Contract\PHPStan\Reflection\TypeToCallReflectionResolver\TypeToCallReflectionResolverInterface;

final class TypeToCallReflectionResolverRegistry
{
    /**
     * @var TypeToCallReflectionResolverInterface[]
     */
    private array $typeToCallReflectionResolvers = [];

    public function __construct(
        ClosureTypeToCallReflectionResolver $closureTypeToCallReflectionResolver,
        ConstantArrayTypeToCallReflectionResolver $constantArrayTypeToCallReflectionResolver,
        ConstantStringTypeToCallReflectionResolver $constantStringTypeToCallReflectionResolver,
        ObjectTypeToCallReflectionResolver $objectTypeToCallReflectionResolver,
    ) {
        $this->typeToCallReflectionResolvers = [
            $closureTypeToCallReflectionResolver,
            $constantArrayTypeToCallReflectionResolver,
            $constantStringTypeToCallReflectionResolver,
            $objectTypeToCallReflectionResolver,
        ];
    }

    public function resolve(Type $type, Scope $scope): FunctionReflection|MethodReflection|null
    {
        foreach ($this->typeToCallReflectionResolvers as $typeToCallReflectionResolver) {
            if (! $typeToCallReflectionResolver->supports($type)) {
                continue;
            }

            return $typeToCallReflectionResolver->resolve($type, $scope);
        }

        return null;
    }
}
