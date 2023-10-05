<?php

declare(strict_types=1);

namespace Rector\FamilyTree\Reflection;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Util\Reflection\PrivatesAccessor;
use Rector\NodeNameResolver\NodeNameResolver;

final class FamilyRelationsAnalyzer
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly PrivatesAccessor $privatesAccessor,
        private readonly NodeNameResolver $nodeNameResolver,
    ) {
    }

    /**
     * @return ClassReflection[]
     */
    public function getChildrenOfClassReflection(ClassReflection $desiredClassReflection): array
    {
        if ($desiredClassReflection->isFinalByKeyword()) {
            return [];
        }

        /** @var ClassReflection[] $classReflections */
        $classReflections = $this->privatesAccessor->getPrivateProperty($this->reflectionProvider, 'classes');

        $childrenClassReflections = [];

        foreach ($classReflections as $classReflection) {
            if (! $classReflection->isSubclassOf($desiredClassReflection->getName())) {
                continue;
            }

            $childrenClassReflections[] = $classReflection;
        }

        return $childrenClassReflections;
    }

    /**
     * @api
     * @return string[]
     */
    public function getClassLikeAncestorNames(Class_ | Name $classOrName): array
    {
        $ancestorNames = [];

        if ($classOrName instanceof Name) {
            $fullName = $this->nodeNameResolver->getName($classOrName);
            $classReflection = $this->reflectionProvider->getClass($fullName);
            $ancestors = array_merge($classReflection->getParents(), $classReflection->getInterfaces());

            return array_map(
                static fn (ClassReflection $classReflection): string => $classReflection->getName(),
                $ancestors
            );
        }

        if ($classOrName->extends instanceof Name) {
            $ancestorNames[] = $this->nodeNameResolver->getName($classOrName->extends);
            $ancestorNames = array_merge($ancestorNames, $this->getClassLikeAncestorNames($classOrName->extends));
        }

        foreach ($classOrName->implements as $implement) {
            $ancestorNames[] = $this->nodeNameResolver->getName($implement);
            $ancestorNames = array_merge($ancestorNames, $this->getClassLikeAncestorNames($implement));
        }

        /** @var string[] $ancestorNames */
        return $ancestorNames;
    }
}
