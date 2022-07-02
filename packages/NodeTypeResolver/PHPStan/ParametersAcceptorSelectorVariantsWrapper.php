<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan;

use PhpParser\Node\Arg;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptor;
use PHPStan\Reflection\ParametersAcceptorSelector;

final class ParametersAcceptorSelectorVariantsWrapper
{
    /**
     * @param Arg[] $args
     */
    public static function select(
        FunctionReflection|MethodReflection $reflection,
        array $args,
        Scope $scope
    ): ParametersAcceptor {
        $variants = $reflection->getVariants();

        return count($variants) > 1
            ? ParametersAcceptorSelector::selectFromArgs($scope, $args, $variants)
            : ParametersAcceptorSelector::selectSingle($variants);
    }
}
