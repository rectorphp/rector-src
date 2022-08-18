<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan;

use PhpParser\Node\Expr\CallLike;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptor;
use PHPStan\Reflection\ParametersAcceptorSelector;

final class ParametersAcceptorSelectorVariantsWrapper
{
    public static function select(
        FunctionReflection|MethodReflection $reflection,
        CallLike $callLike,
        Scope $scope
    ): ParametersAcceptor {
        $variants = $reflection->getVariants();

        return count($variants) > 1
            ? ParametersAcceptorSelector::selectFromArgs($scope, $callLike->getArgs(), $variants)
            : ParametersAcceptorSelector::selectSingle($variants);
    }
}
