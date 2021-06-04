<?php

declare(strict_types=1);

namespace Rector\DowngradePhp72\NodeAnalyzer;

use PHPStan\Reflection\ClassReflection;
use Rector\NodeCollector\NodeCollector\NodeRepository;
use Rector\NodeNameResolver\NodeNameResolver;

final class ParamContravariantDetector
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private NodeRepository $nodeRepository
    ) {
    }

    /**
     * @param ClassReflection[] $ancestors
     */
    public function hasParentMethod(ClassReflection $classReflection, array $ancestors, string $classMethodName): bool
    {
        foreach ($ancestors as $ancestor) {
            if ($classReflection === $ancestor) {
                continue;
            }
            $ancestorHasMethod = $ancestor->hasMethod($classMethodName);

            if ($ancestorHasMethod) {
                return true;
            }
        }

        return false;
    }

    public function hasChildMethod(ClassReflection $classReflection, string $classMethodName): bool
    {
        $classLikes = $this->nodeRepository->findClassesAndInterfacesByType($classReflection->getName());
        foreach ($classLikes as $classLike) {
            $currentClassMethod = $classLike->getMethod($classMethodName);
            if ($currentClassMethod !== null) {
                return true;
            }
        }

        return false;
    }
}
