<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\NullType;
use Rector\Reflection\ReflectionResolver;

final readonly class CallLikeParamDefaultResolver
{
    public function __construct(
        private ReflectionResolver $reflectionResolver,
    ) {
    }

    /**
     * @return int[]
     */
    public function resolveNullPositions(MethodCall|StaticCall|New_|FuncCall $callLike): array
    {
        $methodReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($callLike);
        if (! $methodReflection instanceof MethodReflection) {
            return [];
        }

        $nullPositions = [];

        $extendedParametersAcceptor = ParametersAcceptorSelector::combineAcceptors($methodReflection->getVariants());
        foreach ($extendedParametersAcceptor->getParameters() as $position => $extendedParameterReflection) {
            if (! $extendedParameterReflection->getDefaultValue() instanceof NullType) {
                continue;
            }

            $nullPositions[] = $position;
        }

        return $nullPositions;
    }

    public function resolvePositionParameterByName(MethodCall|StaticCall|New_|FuncCall $callLike, string $parameterName): ?int
    {
        $methodReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($callLike);
        if (! $methodReflection instanceof MethodReflection) {
            return null;
        }

        $extendedParametersAcceptor = ParametersAcceptorSelector::combineAcceptors($methodReflection->getVariants());
        foreach ($extendedParametersAcceptor->getParameters() as $position => $extendedParameterReflection) {
            if ($extendedParameterReflection->getName() === $parameterName) {
                return $position;
            }
        }

        return null;
    }
}
