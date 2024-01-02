<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\Type;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpParser\AstResolver;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\TypeDeclaration\TypeInferer\AssignToPropertyTypeInferer;
use Rector\ValueObject\Application\File;

final readonly class AllAssignNodePropertyTypeInferer
{
    public function __construct(
        private AssignToPropertyTypeInferer $assignToPropertyTypeInferer,
        private NodeNameResolver $nodeNameResolver,
        private AstResolver $astResolver,
        private BetterNodeFinder $betterNodeFinder
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
