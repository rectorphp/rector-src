<?php

declare(strict_types=1);

namespace Rector\Php71\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassLike;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Php\PhpPropertyReflection;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\UnionType;
use Rector\Core\NodeAnalyzer\PropertyFetchAnalyzer;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\TypeDeclaration\AlreadyAssignDetector\ConstructorAssignDetector;

final class CountableAnalyzer
{
    public function __construct(
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly PropertyFetchAnalyzer $propertyFetchAnalyzer,
        private readonly ConstructorAssignDetector $constructorAssignDetector
    ) {
    }

    public function isCastableArrayType(Expr $expr, ArrayType $arrayType, Scope $scope): bool
    {
        if (! $this->propertyFetchAnalyzer->isPropertyFetch($expr)) {
            return false;
        }

        if ($arrayType instanceof ConstantArrayType) {
            return false;
        }

        /** @var StaticPropertyFetch|PropertyFetch $expr */
        $callerObjectType = $expr instanceof StaticPropertyFetch
            ? $this->nodeTypeResolver->getType($expr->class)
            : $this->nodeTypeResolver->getType($expr->var);

        $propertyName = $this->nodeNameResolver->getName($expr->name);
        if (! is_string($propertyName)) {
            return false;
        }

        if ($callerObjectType instanceof UnionType) {
            $callerObjectType = $callerObjectType->getTypes()[0];
        }

        if (! $callerObjectType instanceof TypeWithClassName) {
            return false;
        }

        if ($this->isCallerObjectClassNameStmtOrArray($callerObjectType)) {
            return false;
        }

        // this must be handled reflection, as PHPStan ReflectionProvider does not provide default values for properties in any way

        $classReflection = $this->reflectionProvider->getClass($callerObjectType->getClassName());

        $nativeReflectionClass = $classReflection->getNativeReflection();
        $propertiesDefaults = $nativeReflectionClass->getDefaultProperties();

        if (! array_key_exists($propertyName, $propertiesDefaults)) {
            return false;
        }

        $phpPropertyReflection = $classReflection->getProperty($propertyName, $scope);
        if (! $phpPropertyReflection instanceof PhpPropertyReflection) {
            return false;
        }

        $nativeType = $phpPropertyReflection->getNativeType();
        if ($this->isIterableOrFilledAtConstruct($nativeType, $expr)) {
            return false;
        }

        $propertyDefaultValue = $propertiesDefaults[$propertyName];
        return $propertyDefaultValue === null;
    }

    private function isCallerObjectClassNameStmtOrArray(TypeWithClassName $typeWithClassName): bool
    {
        if (is_a($typeWithClassName->getClassName(), Stmt::class, true)) {
            return true;
        }

        return is_a($typeWithClassName->getClassName(), Array_::class, true);
    }

    private function isIterableOrFilledAtConstruct(
        Type $nativeType,
        StaticPropertyFetch|PropertyFetch $propertyFetch
    ): bool {
        if ($nativeType->isIterable()->yes()) {
            return true;
        }

        $classLike = $this->betterNodeFinder->findParentType($propertyFetch, ClassLike::class);
        if (! $classLike instanceof ClassLike) {
            return false;
        }

        if ($propertyFetch->name instanceof Expr) {
            return false;
        }

        $propertyName = (string) $this->nodeNameResolver->getName($propertyFetch->name);
        return $this->constructorAssignDetector->isPropertyAssigned($classLike, $propertyName);
    }
}
