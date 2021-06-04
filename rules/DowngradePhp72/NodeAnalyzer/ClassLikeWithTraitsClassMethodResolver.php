<?php

declare(strict_types=1);

namespace Rector\DowngradePhp72\NodeAnalyzer;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\NodeCollector\NodeCollector\NodeRepository;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class ClassLikeWithTraitsClassMethodResolver
{
    public function __construct(
        private NodeRepository $nodeRepository
    ) {
    }

    /**
     * @param ClassReflection $ancestors
     * @return ClassMethod[]
     */
    public function resolve(array $ancestors): array
    {
        $classMethods = [];
        foreach ($ancestors as $ancestorClassReflection) {
            $ancestorClassLike = $this->nodeRepository->findClassLike($ancestorClassReflection->getName());
            if (! $ancestorClassLike instanceof ClassLike) {
                continue;
            }

            $classMethods = array_merge($classMethods, $ancestorClassLike->getMethods());
        }

        return $classMethods;
    }
}
