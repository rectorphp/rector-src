<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan;

use PhpParser\Node\Arg;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ParametersAcceptor;
use PHPStan\Reflection\ParametersAcceptorSelector;

final class ParametersAcceptorSelectorVariantsWrapper
{
    /**
     * @param ParametersAcceptor[] $variants
     * @param Arg[] $args
     */
    public static function select(array $variants, array $args, Scope $scope): ParametersAcceptor
    {
        if (count($variants) > 1) {
            return ParametersAcceptorSelector::selectFromArgs($scope, $args, $variants);
        }

        return ParametersAcceptorSelector::selectSingle($variants);
    }
}
