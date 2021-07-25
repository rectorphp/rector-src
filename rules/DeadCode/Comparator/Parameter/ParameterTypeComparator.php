<?php

declare(strict_types=1);

namespace Rector\DeadCode\Comparator\Parameter;

use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\NodeTypeResolver\MethodParameterTypeResolver;

final class ParameterTypeComparator
{
    public function __construct(
        private MethodParameterTypeResolver $methodParameterTypeResolver
    ) {
    }

    public function isClassMethodIdenticalToParentStaticCall(
        ClassMethod $classMethod,
        StaticCall $staticCall
    ): bool {
        $currentParameterTypes = $this->methodParameterTypeResolver->provideParameterTypesByClassMethod($classMethod);
        $parentParameterTypes = $this->methodParameterTypeResolver->provideParameterTypesByStaticCall($staticCall);

        foreach ($currentParameterTypes as $key => $currentParameterType) {
            if (! isset($parentParameterTypes[$key])) {
                continue;
            }

            $parentParameterType = $parentParameterTypes[$key];
            if (! $currentParameterType->equals($parentParameterType)) {
                return false;
            }
        }

        return true;
    }
}
