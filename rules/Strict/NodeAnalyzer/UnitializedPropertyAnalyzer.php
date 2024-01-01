<?php

declare(strict_types=1);

namespace Rector\Strict\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ThisType;
use PHPStan\Type\TypeWithClassName;
use Rector\Core\PhpParser\AstResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\TypeDeclaration\AlreadyAssignDetector\ConstructorAssignDetector;

final class UnitializedPropertyAnalyzer
{
    public function __construct(
        private readonly AstResolver $astResolver,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly ConstructorAssignDetector $constructorAssignDetector,
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    public function isUnitialized(Expr $expr): bool
    {
        if (! $expr instanceof PropertyFetch) {
            return false;
        }

        $varType = $this->nodeTypeResolver->getType($expr->var);

        if ($varType instanceof ThisType) {
            $varType = $varType->getStaticObjectType();
        }

        if (! $varType instanceof TypeWithClassName) {
            return false;
        }

        $className = $varType->getClassName();
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
