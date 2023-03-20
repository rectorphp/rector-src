<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan;

use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\FunctionLike;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptor;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\ParametersAcceptorWithPhpDocs;

final class ParametersAcceptorSelectorVariantsWrapper
{
    public static function select(
        FunctionReflection|MethodReflection $reflection,
        CallLike|FunctionLike $node,
        Scope $scope
    ): ParametersAcceptor {
        $variants = $reflection->getVariants();
        if ($node instanceof FunctionLike) {
            return self::selectFromVariants($variants);
        }

        if ($node->isFirstClassCallable()) {
            return ParametersAcceptorSelector::selectSingle($variants);
        }

        // on CallLike, using count() check to fallback to use selectSingle()
        // is more performance than direct selectFromArgs()
        return count($variants) > 1
            ? ParametersAcceptorSelector::selectFromArgs($scope, $node->getArgs(), $variants)
            : ParametersAcceptorSelector::selectSingle($variants);
    }

    /**
     * @param ParametersAcceptor[]  $variants
     */
    public static function selectFromVariants(array $variants): ParametersAcceptorWithPhpDocs
    {
        $parameterAcceptors = [];
        foreach ($variants as $variant) {
            $parameterAcceptors[] = ParametersAcceptorSelector::selectSingle([$variant]);
        }

        return ParametersAcceptorSelector::combineAcceptors($parameterAcceptors);
    }
}
