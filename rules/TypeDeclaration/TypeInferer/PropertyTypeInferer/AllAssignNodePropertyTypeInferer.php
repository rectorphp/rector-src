<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\Type;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\TypeDeclaration\TypeInferer\AssignToPropertyTypeInferer;

final class AllAssignNodePropertyTypeInferer
{
    public function __construct(
        private readonly AssignToPropertyTypeInferer $assignToPropertyTypeInferer,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly AstResolver $astResolver,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    public function inferProperty(Property $property, ClassReflection $classReflection, File $file): ?Type
    {
        if ($classReflection->getFileName() === $file->getFilePath()) {
            $className = $classReflection->getName();
            $classLike = $this->betterNodeFinder->findFirst(
                $file->getNewStmts(),
                fn (Node $node): bool => $node instanceof ClassLike && $this->nodeNameResolver->isName(
                    $node,
                    $className
                )
            );
        } else {
            $classLike = $this->astResolver->resolveClassFromClassReflection($classReflection);
        }

        if (! $classLike instanceof ClassLike) {
            return null;
        }

        $propertyName = $this->nodeNameResolver->getName($property);

        return $this->assignToPropertyTypeInferer->inferPropertyInClassLike($property, $propertyName, $classLike);
    }
}
