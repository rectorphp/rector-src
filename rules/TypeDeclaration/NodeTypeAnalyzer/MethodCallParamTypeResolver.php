<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeTypeAnalyzer;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\Type;
use Rector\Reflection\ReflectionResolver;

final readonly class MethodCallParamTypeResolver
{
    public function __construct(
        private ReflectionResolver $reflectionResolver,
    ) {
    }

    /**
     * @return array<int, Type>
     */
    public function resolve(MethodCall $methodCall): array
    {
        $methodReflection = $this->reflectionResolver->resolveMethodReflectionFromMethodCall($methodCall);
        if (! $methodReflection instanceof MethodReflection) {
            return [];
        }

        $extendedParametersAcceptor = ParametersAcceptorSelector::combineAcceptors($methodReflection->getVariants());

        $typeByPosition = [];
        foreach ($extendedParametersAcceptor->getParameters() as $position => $parameterReflection) {
            $typeByPosition[$position] = $parameterReflection->getNativeType();
        }

        return $typeByPosition;
    }
}
