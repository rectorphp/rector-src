<?php

declare(strict_types=1);

namespace Rector\Php80\NodeResolver;

use PhpParser\Node\FunctionLike;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParameterReflection;

final class RequireOptionalParamResolver
{
    /**
     * @return ParameterReflection[]
     */
    public function resolveFromReflection(MethodReflection | FunctionReflection $functionLikeReflection): array
    {
        $parametersAcceptor = $functionLikeReflection->getVariants()[0];

        $optionalParams = [];
        $requireParams = [];

        foreach ($parametersAcceptor->getParameters() as $position => $parameterReflection) {
            if ($parameterReflection->getDefaultValue() === null && ! $parameterReflection->isVariadic()) {
                $requireParams[$position] = $parameterReflection;
            } else {
                $optionalParams[$position] = $parameterReflection;
            }
        }

        return $requireParams + $optionalParams;
    }

    /**
     * @param ClassMethod $functionLike
     * @return Param[]
     */
    public function resolve(FunctionLike $functionLike): array
    {
        $optionalParams = [];
        $requireParams = [];
        foreach ($functionLike->getParams() as $position => $param) {
            if ($param->default === null && ! $param->variadic) {
                $requireParams[$position] = $param;
            } else {
                $optionalParams[$position] = $param;
            }
        }

        return $requireParams + $optionalParams;
    }
}
