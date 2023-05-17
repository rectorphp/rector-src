<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver;

use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\Native\NativeMethodReflection;
use PHPStan\Type\Type;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\NodeTypeResolver\PHPStan\ParametersAcceptorSelectorVariantsWrapper;

final class MethodParameterTypeResolver
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
    ) {
    }

    /**
     * @return Type[]
     */
    public function provideParameterTypesByStaticCall(StaticCall $staticCall, Scope $scope): array
    {
        $methodReflection = $this->reflectionResolver->resolveMethodReflectionFromStaticCall($staticCall);
        if (! $methodReflection instanceof MethodReflection) {
            return [];
        }

        return $this->provideParameterTypesFromMethodReflection($methodReflection, $staticCall, $scope);
    }

    /**
     * @return Type[]
     */
    public function provideParameterTypesByClassMethod(ClassMethod $classMethod, Scope $scope): array
    {
        $methodReflection = $this->reflectionResolver->resolveMethodReflectionFromClassMethod($classMethod);
        if (! $methodReflection instanceof MethodReflection) {
            return [];
        }

        return $this->provideParameterTypesFromMethodReflection($methodReflection, $classMethod, $scope);
    }

    /**
     * @return Type[]
     */
    private function provideParameterTypesFromMethodReflection(
        MethodReflection $methodReflection,
        ClassMethod|StaticCall $node,
        Scope $scope
    ): array {
        if ($methodReflection instanceof NativeMethodReflection) {
            // method "getParameters()" does not exist there
            return [];
        }

        $parameterTypes = [];
        $parametersAcceptor = ParametersAcceptorSelectorVariantsWrapper::select($methodReflection, $node, $scope);

        foreach ($parametersAcceptor->getParameters() as $parameterReflection) {
            $parameterTypes[] = $parameterReflection->getType();
        }

        return $parameterTypes;
    }
}
