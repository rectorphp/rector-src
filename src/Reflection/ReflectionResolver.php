<?php

declare(strict_types=1);

namespace Rector\Core\Reflection;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\Php\PhpPropertyReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\TypeUtils;
use PHPStan\Type\TypeWithClassName;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\StaticTypeMapper\ValueObject\Type\ShortenedObjectType;

final class ReflectionResolver
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly MethodReflectionResolver $methodReflectionResolver,
        private readonly AstResolver $astResolver
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
        MethodCall|StaticCall|PropertyFetch|StaticPropertyFetch $node
    ): ?ClassReflection {
        if ($node instanceof PropertyFetch || $node instanceof StaticPropertyFetch) {
            $objectType = $node instanceof PropertyFetch
                ? $this->nodeTypeResolver->getType($node->var)
                : $this->nodeTypeResolver->getType($node->class);

            if (! $objectType instanceof TypeWithClassName) {
                return null;
            }

            $className = $objectType->getClassName();
            if (! $this->reflectionProvider->hasClass($className)) {
                return null;
            }

            return $this->reflectionProvider->getClass($className);
        }

        $classMethod = $this->astResolver->resolveClassMethodFromCall($node);
        return $this->resolveClassReflection($classMethod);
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
            $classNames = TypeUtils::getDirectClassNames($objectType);
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
        if (! $callerType instanceof TypeWithClassName) {
            return null;
        }

        $methodName = $this->nodeNameResolver->getName($methodCall->name);
        if ($methodName === null) {
            return null;
        }

        $scope = $methodCall->getAttribute(AttributeKey::SCOPE);
        return $this->resolveMethodReflection($callerType->getClassName(), $methodName, $scope);
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

    public function resolveMethodReflectionFromNew(New_ $new): ?MethodReflection
    {
        $newClassType = $this->nodeTypeResolver->getType($new->class);
        if (! $newClassType instanceof TypeWithClassName) {
            return null;
        }

        $scope = $new->getAttribute(AttributeKey::SCOPE);
        return $this->resolveMethodReflection($newClassType->getClassName(), MethodName::CONSTRUCT, $scope);
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

        if (! $fetcheeType instanceof TypeWithClassName) {
            return null;
        }

        if (! $this->reflectionProvider->hasClass($fetcheeType->getClassName())) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($fetcheeType->getClassName());

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
