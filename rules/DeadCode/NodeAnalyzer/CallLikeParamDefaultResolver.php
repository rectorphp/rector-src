<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\NullType;
use Rector\Reflection\ReflectionResolver;

final class CallLikeParamDefaultResolver
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
    ) {
    }

    /**
     * @return int[]
     */
    public function resolveNullPositions(MethodCall|StaticCall|New_ $callLike): array
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
}
