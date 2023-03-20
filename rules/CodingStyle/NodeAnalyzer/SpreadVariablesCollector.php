<?php

declare(strict_types=1);

namespace Rector\CodingStyle\NodeAnalyzer;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\ParametersAcceptorSelectorVariantsWrapper;

final class SpreadVariablesCollector
{
    /**
     * @return array<int, ParameterReflection>
     */
    public function resolveFromMethodReflection(MethodReflection $methodReflection): array
    {
        $spreadParameterReflections = [];

        $parametersAcceptorWithPhpDocs = ParametersAcceptorSelectorVariantsWrapper::selectFromVariants($methodReflection->getVariants());
        foreach ($parametersAcceptorWithPhpDocs->getParameters() as $key => $parameterReflectionWithPhpDoc) {
            if (! $parameterReflectionWithPhpDoc->isVariadic()) {
                continue;
            }

            $spreadParameterReflections[$key] = $parameterReflectionWithPhpDoc;
        }

        return $spreadParameterReflections;
    }

    /**
     * @return array<int, Param>
     */
    public function resolveFromClassMethod(ClassMethod $classMethod): array
    {
        /** @var array<int, Param> $spreadParams */
        $spreadParams = [];

        foreach ($classMethod->params as $key => $param) {
            // prevent race-condition removal on class method
            $originalParam = $param->getAttribute(AttributeKey::ORIGINAL_NODE);
            if (! $originalParam instanceof Param) {
                continue;
            }

            if (! $originalParam->variadic) {
                continue;
            }

            $spreadParams[$key] = $param;
        }

        return $spreadParams;
    }
}
