<?php

declare(strict_types=1);

namespace Rector\Reflection;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\Php\PhpPropertyReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\BenevolentUnionType;
use PHPStan\Type\TypeCombinator;
use Rector\Exception\ShouldNotHappenException;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\StaticTypeMapper\Resolver\ClassNameFromObjectTypeResolver;
use Rector\StaticTypeMapper\ValueObject\Type\ShortenedObjectType;
use Rector\ValueObject\MethodName;

final readonly class ReflectionResolver
{
    public function __construct(
        private ReflectionProvider $reflectionProvider,
        private NodeTypeResolver $nodeTypeResolver,
        private NodeNameResolver $nodeNameResolver,
        private ClassAnalyzer $classAnalyzer,
        private MethodReflectionResolver $methodReflectionResolver
    ) {
    }

    /**
     * @api
     */
    public function resolveClassAndAnonymousClass(ClassLike $classLike): ClassReflection
    {
        if ($classLike instanceof Class_ && $this->classAnalyzer->isAnonymousClass($classLike)) {
            $classLikeScope = $classLike->getAttribute(AttributeKey::SCOPE);
            if (! $classLikeScope instanceof Scope) {
                throw new ShouldNotHappenException();
            }

            return $this->reflectionProvider->getAnonymousClassReflection($classLike, $classLikeScope);
        }

        $className = (string) $this->nodeNameResolver->getName($classLike);
        return $this->reflectionProvider->getClass($className);
    }

    public function resolveClassReflection(?Node $node): ?ClassReflection
    {
        if (! $node instanceof Node) {
            return null;
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return null;
        }

        return $scope->getClassReflection();
    }

    public function resolveClassReflectionSourceObject(
        MethodCall|NullsafeMethodCall|StaticCall|PropertyFetch|StaticPropertyFetch $node
    ): ?ClassReflection {
        $objectType = $node instanceof StaticCall || $node instanceof StaticPropertyFetch
            ? $this->nodeTypeResolver->getType($node->class)
            : $this->nodeTypeResolver->getType($node->var);

        $className = ClassNameFromObjectTypeResolver::resolve($objectType);
        if ($className === null) {
            return null;
        }

        if (! $this->reflectionProvider->hasClass($className)) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        if ($node instanceof PropertyFetch || $node instanceof StaticPropertyFetch) {
            $propertyName = (string) $this->nodeNameResolver->getName($node->name);
            if (! $classReflection->hasNativeProperty($propertyName)) {
                return null;
            }

            $property = $classReflection->getNativeProperty($propertyName);
            if ($property->isPrivate()) {
                return $classReflection;
            }

            if ($this->reflectionProvider->hasClass($property->getDeclaringClass()->getName())) {
                return $this->reflectionProvider->getClass($property->getDeclaringClass()->getName());
            }

            return $classReflection;
        }

        $methodName = (string) $this->nodeNameResolver->getName($node->name);
        if (! $classReflection->hasNativeMethod($methodName)) {
            return null;
        }

        $extendedMethodReflection = $classReflection->getNativeMethod($methodName);
        if ($extendedMethodReflection->isPrivate()) {
            return $classReflection;
        }

        if ($this->reflectionProvider->hasClass($extendedMethodReflection->getDeclaringClass()->getName())) {
            return $this->reflectionProvider->getClass($extendedMethodReflection->getDeclaringClass()->getName());
        }

        return $classReflection;
    }

    /**
     * @param class-string $className
     */
    public function resolveMethodReflection(string $className, string $methodName, ?Scope $scope): ?MethodReflection
    {
        return $this->methodReflectionResolver->resolveMethodReflection($className, $methodName, $scope);
    }

    public function resolveMethodReflectionFromStaticCall(StaticCall $staticCall): ?MethodReflection
    {
        $objectType = $this->nodeTypeResolver->getType($staticCall->class);

        if ($objectType instanceof ShortenedObjectType) {
            /** @var array<class-string> $classNames */
            $classNames = [$objectType->getFullyQualifiedName()];
        } else {
            /** @var array<class-string> $classNames */
            $classNames = $objectType->getObjectClassNames();
        }

        $methodName = $this->nodeNameResolver->getName($staticCall->name);
        if ($methodName === null) {
            return null;
        }

        $scope = $staticCall->getAttribute(AttributeKey::SCOPE);

        foreach ($classNames as $className) {
            $methodReflection = $this->resolveMethodReflection($className, $methodName, $scope);
            if ($methodReflection instanceof MethodReflection) {
                return $methodReflection;
            }
        }

        return null;
    }

    public function resolveMethodReflectionFromMethodCall(MethodCall $methodCall): ?MethodReflection
    {
        $callerType = $this->nodeTypeResolver->getType($methodCall->var);

        if ($callerType instanceof BenevolentUnionType) {
            $callerType = TypeCombinator::removeFalsey($callerType);
        }

        $className = ClassNameFromObjectTypeResolver::resolve($callerType);
        if ($className === null) {
            return null;
        }

        $methodName = $this->nodeNameResolver->getName($methodCall->name);
        if ($methodName === null) {
            return null;
        }

        $scope = $methodCall->getAttribute(AttributeKey::SCOPE);
        return $this->resolveMethodReflection($className, $methodName, $scope);
    }

    public function resolveFunctionLikeReflectionFromCall(
        MethodCall|FuncCall|StaticCall $call
    ): MethodReflection | FunctionReflection | null {
        if ($call instanceof MethodCall) {
            return $this->resolveMethodReflectionFromMethodCall($call);
        }

        if ($call instanceof StaticCall) {
            return $this->resolveMethodReflectionFromStaticCall($call);
        }

        return $this->resolveFunctionReflectionFromFuncCall($call);
    }

    public function resolveMethodReflectionFromClassMethod(ClassMethod $classMethod, Scope $scope): ?MethodReflection
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        $className = $classReflection->getName();
        $methodName = $this->nodeNameResolver->getName($classMethod);

        return $this->resolveMethodReflection($className, $methodName, $scope);
    }

    public function resolveFunctionReflectionFromFunction(Function_ $function, Scope $scope): ?FunctionReflection
    {
        $name = $this->nodeNameResolver->getName($function);
        if ($name === null) {
            return null;
        }

        $functionName = new Name($name);
        if ($this->reflectionProvider->hasFunction($functionName, $scope)) {
            return $this->reflectionProvider->getFunction($functionName, $scope);
        }

        return null;
    }

    public function resolveMethodReflectionFromNew(New_ $new): ?MethodReflection
    {
        $newClassType = $this->nodeTypeResolver->getType($new->class);
        $className = ClassNameFromObjectTypeResolver::resolve($newClassType);

        if ($className === null) {
            return null;
        }

        $scope = $new->getAttribute(AttributeKey::SCOPE);
        return $this->resolveMethodReflection($className, MethodName::CONSTRUCT, $scope);
    }

    public function resolvePropertyReflectionFromPropertyFetch(
        PropertyFetch | StaticPropertyFetch $propertyFetch
    ): ?PhpPropertyReflection {
        $propertyName = $this->nodeNameResolver->getName($propertyFetch->name);
        if ($propertyName === null) {
            return null;
        }

        $fetcheeType = $propertyFetch instanceof PropertyFetch
            ? $this->nodeTypeResolver->getType($propertyFetch->var)
            : $this->nodeTypeResolver->getType($propertyFetch->class);

        $className = ClassNameFromObjectTypeResolver::resolve($fetcheeType);
        if ($className === null) {
            return null;
        }

        if (! $this->reflectionProvider->hasClass($className)) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        if (! $classReflection->hasProperty($propertyName)) {
            return null;
        }

        $scope = $propertyFetch->getAttribute(AttributeKey::SCOPE);
        if ($scope instanceof Scope) {
            $propertyReflection = $classReflection->getProperty($propertyName, $scope);
            if ($propertyReflection instanceof PhpPropertyReflection) {
                return $propertyReflection;
            }

            return null;
        }

        return $classReflection->getNativeProperty($propertyName);
    }

    private function resolveFunctionReflectionFromFuncCall(
        FuncCall $funcCall
    ): FunctionReflection | MethodReflection | null {
        $scope = $funcCall->getAttribute(AttributeKey::SCOPE);

        if (! $funcCall->name instanceof Name) {
            return null;
        }

        if ($this->reflectionProvider->hasFunction($funcCall->name, $scope)) {
            return $this->reflectionProvider->getFunction($funcCall->name, $scope);
        }

        return null;
    }
}
