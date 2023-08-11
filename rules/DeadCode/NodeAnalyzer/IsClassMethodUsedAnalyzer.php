<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\Analyser\Scope;
use PHPStan\Parser\ArrayMapArgVisitor;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\Enum\ObjectReference;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\NodeCollector\NodeAnalyzer\ArrayCallableMethodMatcher;
use Rector\NodeCollector\ValueObject\ArrayCallable;
use Rector\NodeCollector\ValueObject\ArrayCallableDynamicMethod;
use Rector\NodeNameResolver\NodeNameResolver;

final class IsClassMethodUsedAnalyzer
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly AstResolver $astResolver,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ValueResolver $valueResolver,
        private readonly ArrayCallableMethodMatcher $arrayCallableMethodMatcher,
        private readonly CallCollectionAnalyzer $callCollectionAnalyzer,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function isClassMethodUsed(Class_ $class, ClassMethod $classMethod, Scope $scope): bool
    {
        $classMethodName = $this->nodeNameResolver->getName($classMethod);

        // 1. direct normal calls
        if ($this->isClassMethodCalledInLocalMethodCall($class, $classMethodName)) {
            return true;
        }

        // 2. direct static calls
        if ($this->isClassMethodUsedInLocalStaticCall($class, $classMethodName)) {
            return true;
        }

        // 3. magic array calls!
        if ($this->isClassMethodCalledInLocalArrayCall($class, $classMethod, $scope)) {
            return true;
        }

        // 4. private method exists in trait and is overwritten by the class
        return $this->doesMethodExistInTrait($classMethod, $classMethodName);
    }

    private function isClassMethodUsedInLocalStaticCall(Class_ $class, string $classMethodName): bool
    {
        $className = (string) $this->nodeNameResolver->getName($class);

        /** @var StaticCall[] $staticCalls */
        $staticCalls = $this->betterNodeFinder->findInstanceOf($class, StaticCall::class);
        return $this->callCollectionAnalyzer->isExists($staticCalls, $classMethodName, $className);
    }

    private function isClassMethodCalledInLocalMethodCall(Class_ $class, string $classMethodName): bool
    {
        $className = (string) $this->nodeNameResolver->getName($class);

        /** @var MethodCall[] $methodCalls */
        $methodCalls = $this->betterNodeFinder->findInstanceOf($class, MethodCall::class);
        return $this->callCollectionAnalyzer->isExists($methodCalls, $classMethodName, $className);
    }

    private function isInArrayMap(Class_ $class, Array_ $array): bool
    {
        if (! $array->getAttribute(ArrayMapArgVisitor::ATTRIBUTE_NAME) instanceof Arg) {
            return false;
        }

        if (count($array->items) !== 2) {
            return false;
        }

        if (! $array->items[1] instanceof ArrayItem) {
            return false;
        }

        $value = $this->valueResolver->getValue($array->items[1]->value);

        if (! is_string($value)) {
            return false;
        }

        return $class->getMethod($value) instanceof ClassMethod;
    }

    private function isClassMethodCalledInLocalArrayCall(Class_ $class, ClassMethod $classMethod, Scope $scope): bool
    {
        /** @var Array_[] $arrays */
        $arrays = $this->betterNodeFinder->findInstanceOf($class, Array_::class);

        foreach ($arrays as $array) {
            if ($this->isInArrayMap($class, $array)) {
                return true;
            }

            $arrayCallable = $this->arrayCallableMethodMatcher->match($array, $scope);
            if ($arrayCallable instanceof ArrayCallableDynamicMethod) {
                return true;
            }

            if ($this->shouldSkipArrayCallable($class, $arrayCallable)) {
                continue;
            }

            // the method is used
            /** @var ArrayCallable $arrayCallable */
            if ($this->nodeNameResolver->isName($classMethod->name, $arrayCallable->getMethod())) {
                return true;
            }
        }

        return false;
    }

    private function shouldSkipArrayCallable(Class_ $class, null | ArrayCallable $arrayCallable): bool
    {
        if (! $arrayCallable instanceof ArrayCallable) {
            return true;
        }

        // is current class method?
        return ! $this->nodeNameResolver->isName($class, $arrayCallable->getClass());
    }

    private function doesMethodExistInTrait(ClassMethod $classMethod, string $classMethodName): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $traits = $this->astResolver->parseClassReflectionTraits($classReflection);
        $className = $classReflection->getName();

        foreach ($traits as $trait) {
            $method = $trait->getMethod($classMethodName);
            if ($method instanceof ClassMethod) {
                return true;
            }

            if ($this->isUsedInsideMethodTrait($trait, $classMethodName, $className)) {
                return true;
            }
        }

        return false;
    }

    private function isUsedInsideMethodTrait(Trait_ $trait, string $classMethodName, string $className): bool
    {
        foreach ($trait->getMethods() as $method) {
            if ($method->isAbstract()) {
                continue;
            }

            /**
             * Trait can't detect class type, so it rely on "this" or "self" or "static" or "ClassName::methodName()" usage...
             */
            $callMethod = $this->betterNodeFinder->findFirstInFunctionLikeScoped(
                $method,
                function (Node $subNode) use ($className, $classMethodName): bool {
                    if ($subNode instanceof MethodCall) {
                        return $this->nodeNameResolver->isName($subNode->var, 'this') && $this->nodeNameResolver->isName($subNode->name, $classMethodName);
                    }

                    if ($subNode instanceof StaticCall) {
                        if (! $subNode->class instanceof Name) {
                            return false;
                        }

                        if ($subNode->class->isSpecialClassName() ||$subNode->class->toString() === $className)
                            return $this->nodeNameResolver->isName($subNode->class, $classMethodName);
                        }

                        return false;
                    }
            );

            if ($callMethod instanceof CallLike) {
                return true;
            }
        }

        return false;
    }
}
