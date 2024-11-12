<?php

declare(strict_types=1);

namespace Rector\Strict\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ThisType;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\AstResolver;
use Rector\StaticTypeMapper\Resolver\ClassNameFromObjectTypeResolver;
use Rector\TypeDeclaration\AlreadyAssignDetector\ConstructorAssignDetector;

final readonly class UnitializedPropertyAnalyzer
{
    public function __construct(
        private AstResolver $astResolver,
        private NodeTypeResolver $nodeTypeResolver,
        private ConstructorAssignDetector $constructorAssignDetector,
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    public function isUnitialized(Expr $expr): bool
    {
        if (! $expr instanceof PropertyFetch && ! $expr instanceof StaticPropertyFetch) {
            return false;
        }

        $varType = $expr instanceof PropertyFetch
            ? $this->nodeTypeResolver->getType($expr->var)
            : $this->nodeTypeResolver->getType($expr->class);

        if ($varType instanceof ThisType) {
            $varType = $varType->getStaticObjectType();
        }

        $className = ClassNameFromObjectTypeResolver::resolve($varType);
        if ($className === null) {
            return false;
        }

        $classLike = $this->astResolver->resolveClassFromName($className);

        if (! $classLike instanceof ClassLike) {
            return false;
        }

        $propertyName = (string) $this->nodeNameResolver->getName($expr);
        $property = $classLike->getProperty($propertyName);

        if (! $property instanceof Property) {
            return false;
        }

        if (count($property->props) !== 1) {
            return false;
        }

        if ($property->props[0]->default instanceof Expr) {
            return false;
        }

        return ! $this->constructorAssignDetector->isPropertyAssigned($classLike, $propertyName);
    }
}
