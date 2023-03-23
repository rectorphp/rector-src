<?php

declare(strict_types=1);

namespace Rector\DeadCode\Comparator\Parameter;

use PHPStan\Type\Type;
use PhpParser\Node\Expr;
use PhpParser\Node\Param;
use PHPStan\Reflection\ParameterReflection;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\DowngradePhp80\Reflection\DefaultParameterValueResolver;

final class ParameterDefaultsComparator
{
    public function __construct(
        private readonly NodeComparator $nodeComparator,
        private readonly DefaultParameterValueResolver $defaultParameterValueResolver
    ) {
    }

    public function areDefaultValuesDifferent(ParameterReflection $parameterReflection, Param $param): bool
    {
        if (!$parameterReflection->getDefaultValue() instanceof Type && !$param->default instanceof Expr) {
            return false;
        }

        if ($this->isMutuallyExclusiveNull($parameterReflection, $param)) {
            return true;
        }

        /** @var Expr $paramDefault */
        $paramDefault = $param->default;

        $defaultValueExpr = $this->defaultParameterValueResolver->resolveFromParameterReflection($parameterReflection);

        return ! $this->nodeComparator->areNodesEqual($paramDefault, $defaultValueExpr);
    }

    private function isMutuallyExclusiveNull(ParameterReflection $parameterReflection, Param $param): bool
    {
        if (!$parameterReflection->getDefaultValue() instanceof Type && $param->default instanceof Expr) {
            return true;
        }

        if (!$parameterReflection->getDefaultValue() instanceof Type) {
            return false;
        }

        return !$param->default instanceof Expr;
    }
}
