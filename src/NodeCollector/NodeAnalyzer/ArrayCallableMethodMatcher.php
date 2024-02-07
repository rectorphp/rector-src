<?php

declare(strict_types=1);

namespace Rector\NodeCollector\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ThisType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use Rector\Enum\ObjectReference;
use Rector\NodeCollector\ValueObject\ArrayCallable;
use Rector\NodeCollector\ValueObject\ArrayCallableDynamicMethod;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Reflection\ReflectionResolver;
use Rector\ValueObject\MethodName;

final readonly class ArrayCallableMethodMatcher
{
    public function __construct(
        private NodeTypeResolver $nodeTypeResolver,
        private ValueResolver $valueResolver,
        private ReflectionProvider $reflectionProvider,
        private ReflectionResolver $reflectionResolver
    ) {
    }

    /**
     * Matches array like: "[$this, 'methodName']" → ['ClassName', 'methodName']
     * Returns ArrayCallableDynamicMethod object when unknown method of callable used, eg: [$this, $other]
     * @see https://github.com/rectorphp/rector-src/pull/908
     * @see https://github.com/rectorphp/rector-src/pull/909
     */
    public function match(Array_ $array, Scope $scope): null | ArrayCallableDynamicMethod | ArrayCallable
    {
        if (count($array->items) !== 2) {
            return null;
        }

        if ($this->shouldSkipNullItems($array)) {
            return null;
        }

        /** @var ArrayItem[] $items */
        $items = $array->items;

        // $this, self, static, FQN
        $firstItemValue = $items[0]->value;

        $callerType = $this->resolveCallerType($firstItemValue, $scope);
        if (! $callerType instanceof TypeWithClassName) {
            return null;
        }

        if ($array->getAttribute(AttributeKey::IS_ARRAY_IN_ATTRIBUTE) === true) {
            return null;
        }

        $values = $this->valueResolver->getValue($array);
        $className = $callerType->getClassName();
        $secondItemValue = $items[1]->value;

        if ($values === null) {
            return new ArrayCallableDynamicMethod();
        }

        if ($this->shouldSkipAssociativeArray($values)) {
            return null;
        }

        if (! $secondItemValue instanceof String_) {
            return null;
        }

        if ($this->isCallbackAtFunctionNames($array, ['register_shutdown_function', 'forward_static_call'])) {
            return null;
        }

        $methodName = $secondItemValue->value;
        if ($methodName === MethodName::CONSTRUCT) {
            return null;
        }

        // skip non-existing methods
        if (! $callerType->hasMethod($methodName)->yes()) {
            return null;
        }

        return new ArrayCallable($firstItemValue, $className, $methodName);
    }

    private function shouldSkipNullItems(Array_ $array): bool
    {
        if (! $array->items[0] instanceof ArrayItem) {
            return true;
        }

        return ! $array->items[1] instanceof ArrayItem;
    }

    private function shouldSkipAssociativeArray(mixed $values): bool
    {
        if (! is_array($values)) {
            return false;
        }

        $keys = array_keys($values);
        return $keys !== [0, 1] && $keys !== [1];
    }

    /**
     * @param string[] $functionNames
     */
    private function isCallbackAtFunctionNames(Array_ $array, array $functionNames): bool
    {
        $fromFuncCallName = $array->getAttribute(AttributeKey::FROM_FUNC_CALL_NAME);
        if ($fromFuncCallName === null) {
            return false;
        }

        return in_array($fromFuncCallName, $functionNames, true);
    }

    private function resolveClassConstFetchType(ClassConstFetch $classConstFetch, Scope $scope): MixedType | ObjectType
    {
        $classConstantReference = $this->valueResolver->getValue($classConstFetch);

        if ($classConstantReference === ObjectReference::STATIC) {
            $classReflection = $this->reflectionResolver->resolveClassReflection($classConstFetch);
            if (! $classReflection instanceof ClassReflection || ! $classReflection->isClass()) {
                return new MixedType();
            }

            $classConstantReference = $classReflection->getName();
        }

        // non-class value
        if (! is_string($classConstantReference)) {
            return new MixedType();
        }

        if (! $this->reflectionProvider->hasClass($classConstantReference)) {
            return new MixedType();
        }

        $classReflection = $this->reflectionProvider->getClass($classConstantReference);
        $hasConstruct = $classReflection->hasMethod(MethodName::CONSTRUCT);

        if (! $hasConstruct) {
            return new ObjectType($classConstantReference, null, $classReflection);
        }

        $extendedMethodReflection = $classReflection->getMethod(MethodName::CONSTRUCT, $scope);
        $parametersAcceptorWithPhpDocs = ParametersAcceptorSelector::selectSingle(
            $extendedMethodReflection->getVariants()
        );

        foreach ($parametersAcceptorWithPhpDocs->getParameters() as $parameterReflectionWithPhpDoc) {
            if (! $parameterReflectionWithPhpDoc->getDefaultValue() instanceof Type) {
                return new MixedType();
            }
        }

        return new ObjectType($classConstantReference, null, $classReflection);
    }

    private function resolveCallerType(Expr $expr, Scope $scope): Type
    {
        if ($expr instanceof ClassConstFetch) {
            // static ::class reference?
            $callerType = $this->resolveClassConstFetchType($expr, $scope);
        } else {
            $callerType = $this->nodeTypeResolver->getType($expr);
        }

        if ($callerType instanceof ThisType) {
            return $callerType->getStaticObjectType();
        }

        return $callerType;
    }
}
