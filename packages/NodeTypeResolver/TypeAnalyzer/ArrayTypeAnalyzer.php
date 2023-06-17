<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\TypeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Php\PhpPropertyReflection;
use PHPStan\Type\Accessory\HasOffsetType;
use PHPStan\Type\Accessory\NonEmptyArrayType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\IterableType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\PhpParser\ClassLikeAstResolver;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class ArrayTypeAnalyzer
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ClassLikeAstResolver $classLikeAstResolver
    ) {
    }

    public function isArrayType(Expr $expr): bool
    {
        $nodeType = $this->nodeTypeResolver->getType($expr);

        if ($this->isIntersectionArrayType($nodeType)) {
            return true;
        }

        // PHPStan false positive, when variable has type[] docblock, but default array is missing
        if (($expr instanceof PropertyFetch || $expr instanceof StaticPropertyFetch)) {
            if ($this->isPropertyFetchWithArrayDefault($expr)) {
                return true;
            }

            if ($this->isPropertyFetchWithArrayDocblockWithoutDefault($expr)) {
                return false;
            }
        }

        if ($nodeType instanceof MixedType) {
            if ($nodeType->isExplicitMixed()) {
                return false;
            }

            if ($this->isPropertyFetchWithArrayDefault($expr)) {
                return true;
            }
        }

        return $nodeType instanceof ArrayType;
    }

    private function isIntersectionArrayType(Type $nodeType): bool
    {
        if (! $nodeType instanceof IntersectionType) {
            return false;
        }

        foreach ($nodeType->getTypes() as $intersectionNodeType) {
            if ($intersectionNodeType instanceof ArrayType) {
                continue;
            }

            if ($intersectionNodeType instanceof HasOffsetType) {
                continue;
            }

            if ($intersectionNodeType instanceof NonEmptyArrayType) {
                continue;
            }

            return false;
        }

        return true;
    }

    private function isPropertyFetchWithArrayDocblockWithoutDefault(Expr $expr): bool
    {
        if (! $expr instanceof PropertyFetch && ! $expr instanceof StaticPropertyFetch) {
            return false;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($expr);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $propertyName = $this->nodeNameResolver->getName($expr->name);
        if ($propertyName === null) {
            return false;
        }

        /** @var ClassLike $classLike */
        $classLike = $this->classLikeAstResolver->resolveClassFromClassReflection($classReflection);
        $property = $classLike->getProperty($propertyName);

        if (! $property instanceof Property) {
            return false;
        }

        $propertyProperty = $property->props[0];
        if ($propertyProperty->default instanceof Array_) {
            return false;
        }

        $propertyPhpDocInfo = $this->phpDocInfoFactory->createFromNode($property);
        if (! $propertyPhpDocInfo instanceof PhpDocInfo) {
            return false;
        }

        $varType = $propertyPhpDocInfo->getVarType();
        return $varType instanceof ArrayType || $varType instanceof ArrayShapeNode || $varType instanceof IterableType;
    }

    /**
     * phpstan bug workaround - https://phpstan.org/r/0443f283-244c-42b8-8373-85e7deb3504c
     */
    private function isPropertyFetchWithArrayDefault(Expr $expr): bool
    {
        if (! $expr instanceof PropertyFetch && ! $expr instanceof StaticPropertyFetch) {
            return false;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($expr);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        /** @var ClassLike $classLike */
        $classLike = $this->classLikeAstResolver->resolveClassFromClassReflection($classReflection);
        $propertyName = $this->nodeNameResolver->getName($expr->name);

        if ($propertyName === null) {
            return false;
        }

        // A. local property
        $property = $classLike->getProperty($propertyName);
        if ($property instanceof Property) {
            $propertyProperty = $property->props[0];
            return $propertyProperty->default instanceof Array_;
        }

        // B. another object property
        $phpPropertyReflection = $this->reflectionResolver->resolvePropertyReflectionFromPropertyFetch($expr);
        if ($phpPropertyReflection instanceof PhpPropertyReflection) {
            $reflectionProperty = $phpPropertyReflection->getNativeReflection();
            $betterReflection = $reflectionProperty->getBetterReflection();
            $defaultValueExpr = $betterReflection->getDefaultValueExpression();

            if (! $defaultValueExpr instanceof Expr) {
                return false;
            }

            $defaultValueType = $this->nodeTypeResolver->getType($defaultValueExpr);
            return $defaultValueType->isArray()
                ->yes();
        }

        return false;
    }
}
