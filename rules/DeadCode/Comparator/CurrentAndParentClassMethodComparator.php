<?php

declare(strict_types=1);

namespace Rector\DeadCode\Comparator;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\VariadicPlaceholder;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ExtendedMethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\Type;
use Rector\Core\Enum\ObjectReference;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\DeadCode\Comparator\Parameter\ParameterDefaultsComparator;
use Rector\DeadCode\Comparator\Parameter\ParameterTypeComparator;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class CurrentAndParentClassMethodComparator
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ParameterDefaultsComparator $parameterDefaultsComparator,
        private readonly ParameterTypeComparator $parameterTypeComparator,
        private readonly NodeComparator $nodeComparator,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function isParentCallMatching(ClassMethod $classMethod, StaticCall $staticCall, Scope $scope): bool
    {
        if (! $this->isSameMethodParentCall($classMethod, $staticCall)) {
            return false;
        }

        if (! $this->areArgsAndParamsEqual($staticCall->args, $classMethod->params)) {
            return false;
        }

        if (! $this->parameterTypeComparator->isClassMethodIdenticalToParentStaticCall($classMethod, $staticCall)) {
            return false;
        }

        return ! $this->isParentClassMethodVisibilityOrDefaultOverride($classMethod, $staticCall, $scope);
    }

    private function isSameMethodParentCall(ClassMethod $classMethod, StaticCall $staticCall): bool
    {
        if (! $this->nodeNameResolver->areNamesEqual($staticCall->name, $classMethod->name)) {
            return false;
        }

        return $this->nodeNameResolver->isName($staticCall->class, ObjectReference::PARENT);
    }

    /**
     * @param Arg[]|VariadicPlaceholder[] $parentStaticCallArgs
     * @param Param[] $currentClassMethodParams
     */
    private function areArgsAndParamsEqual(array $parentStaticCallArgs, array $currentClassMethodParams): bool
    {
        if (count($parentStaticCallArgs) !== count($currentClassMethodParams)) {
            return false;
        }

        if ($parentStaticCallArgs === []) {
            return true;
        }

        foreach ($parentStaticCallArgs as $key => $arg) {
            if (! isset($currentClassMethodParams[$key])) {
                return false;
            }

            if (! $arg instanceof Arg) {
                continue;
            }

            // this only compares variable name, but those can be differnt, so its kinda useless
            $param = $currentClassMethodParams[$key];
            if (! $this->nodeComparator->areNodesEqual($param->var, $arg->value)) {
                return false;
            }
        }

        return true;
    }

    private function isParentClassMethodVisibilityOrDefaultOverride(
        ClassMethod $classMethod,
        StaticCall $staticCall,
        Scope $scope
    ): bool {
        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $methodName = $this->nodeNameResolver->getName($staticCall->name);
        if ($methodName === null) {
            return false;
        }

        foreach ($classReflection->getParents() as $parentClassReflection) {
            if (! $parentClassReflection->hasMethod($methodName)) {
                continue;
            }

            $nativeParentClassReflection = $parentClassReflection->getNativeReflection();
            $nativeParentClassMethodReflection = $nativeParentClassReflection->getMethod($methodName);

            if (! $nativeParentClassMethodReflection->isProtected()) {
                return $this->isOverridingParentParameters($classMethod, $parentClassReflection, $methodName, $scope);
            }

            if (! $nativeParentClassMethodReflection->isPublic()) {
                return $this->isOverridingParentParameters($classMethod, $parentClassReflection, $methodName, $scope);
            }

            return true;
        }

        return false;
    }

    private function isOverridingParentParameters(
        ClassMethod $classMethod,
        ClassReflection $classReflection,
        string $methodName,
        Scope $scope
    ): bool {
        $extendedMethodReflection = $classReflection->getMethod($methodName, $scope);

        // 3rd party code
        if (! $extendedMethodReflection->isPrivate() && ! $extendedMethodReflection->isPublic() && $classMethod->isPublic()) {
            return true;
        }

        if ($extendedMethodReflection->isInternal()->yes()) {
            // we can't know for certain so we assume its a override with purpose
            return true;
        }

        return $this->areParameterDefaultsDifferent($classMethod, $extendedMethodReflection);
    }

    private function areParameterDefaultsDifferent(
        ClassMethod $classMethod,
        ExtendedMethodReflection $extendedMethodReflection
    ): bool {
        $parametersAcceptorWithPhpDocs = ParametersAcceptorSelector::selectSingle(
            $extendedMethodReflection->getVariants()
        );

        foreach ($parametersAcceptorWithPhpDocs->getParameters() as $key => $parameterReflectionWithPhpDoc) {
            if (! isset($classMethod->params[$key])) {
                if ($parameterReflectionWithPhpDoc->getDefaultValue() instanceof Type) {
                    continue;
                }

                return true;
            }

            $methodParam = $classMethod->params[$key];

            if ($this->parameterDefaultsComparator->areDefaultValuesDifferent(
                $parameterReflectionWithPhpDoc,
                $methodParam
            )) {
                return true;
            }
        }

        return false;
    }
}
