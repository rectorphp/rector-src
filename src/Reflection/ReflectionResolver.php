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
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PHPStan\Reflection\TypeToCallReflectionResolver\TypeToCallReflectionResolverRegistry;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

final class ReflectionResolver
{
    private AstResolver $astResolver;

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly TypeToCallReflectionResolverRegistry $typeToCallReflectionResolverRegistry,
        private readonly ClassAnalyzer $classAnalyzer
    ) {
    }

    #[Required]
    public function autowire(AstResolver $astResolver): void
    {
        $this->astResolver = $astResolver;
    }

    /**
     * @api
     */
    public function resolveClassAndAnonymousClass(ClassLike $classLike): ClassReflection
    {
        if ($classLike instanceof Class_ && $this->classAnalyzer->isAnonymousClass($classLike)) {
            return $this->reflectionProvider->getAnonymousClassReflection(
                $classLike,
                $classLike->getAttribute(AttributeKey::SCOPE)
            );
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

    public function resolveClassReflectionSourceObject(MethodCall|StaticCall $call): ?ClassReflection
    {
        $classMethod = $this->astResolver->resolveClassMethodFromCall($call);
        return $this->resolveClassReflection($classMethod);
    }

    /**
     * @param class-string $className
     */
    public function resolveMethodReflection(string $className, string $methodName, ?Scope $scope): ?MethodReflection
    {
        if (! $this->reflectionProvider->hasClass($className)) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        // better, with support for "@method" annotation methods
        if ($scope instanceof Scope) {
            if ($classReflection->hasMethod($methodName)) {
                return $classReflection->getMethod($methodName, $scope);
            }
        } elseif ($classReflection->hasNativeMethod($methodName)) {
            return $classReflection->getNativeMethod($methodName);
        }

        return null;
    }

    public function resolveMethodReflectionFromStaticCall(StaticCall $staticCall): ?MethodReflection
    {
        $objectType = $this->nodeTypeResolver->getType($staticCall->class);

        /** @var array<class-string> $classNames */
        $classNames = TypeUtils::getDirectClassNames($objectType);

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

    public function resolveMethodReflectionFromClassMethod(ClassMethod $classMethod): ?MethodReflection
    {
        $classLike = $this->betterNodeFinder->findParentType($classMethod, ClassLike::class);
        if (! $classLike instanceof ClassLike) {
            return null;
        }

        $className = $this->nodeNameResolver->getName($classLike);
        if (! is_string($className)) {
            return null;
        }

        $methodName = $this->nodeNameResolver->getName($classMethod);
        $scope = $classMethod->getAttribute(AttributeKey::SCOPE);

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

        $propertyName = $this->nodeNameResolver->getName($propertyFetch->name);
        if ($propertyName === null) {
            return null;
        }

        if (! $classReflection->hasProperty($propertyName)) {
            return null;
        }

        $scope = $propertyFetch->getAttribute(AttributeKey::SCOPE);
        if ($scope instanceof Scope) {
            $propertyRelfection = $classReflection->getProperty($propertyName, $scope);
            if ($propertyRelfection instanceof PhpPropertyReflection) {
                return $propertyRelfection;
            }

            return null;
        }

        return $classReflection->getNativeProperty($propertyName);
    }

    private function resolveFunctionReflectionFromFuncCall(
        FuncCall $funcCall
    ): FunctionReflection | MethodReflection | null {
        $scope = $funcCall->getAttribute(AttributeKey::SCOPE);

        if ($funcCall->name instanceof Name) {
            if ($this->reflectionProvider->hasFunction($funcCall->name, $scope)) {
                return $this->reflectionProvider->getFunction($funcCall->name, $scope);
            }

            return null;
        }

        if (! $scope instanceof Scope) {
            return null;
        }

        // fallback to callable
        $funcCallNameType = $scope->getType($funcCall->name);
        return $this->typeToCallReflectionResolverRegistry->resolve($funcCallNameType, $scope);
    }
}
