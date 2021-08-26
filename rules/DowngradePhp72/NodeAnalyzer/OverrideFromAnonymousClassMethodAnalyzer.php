<?php

declare(strict_types=1);

namespace Rector\DowngradePhp72\NodeAnalyzer;

use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\FamilyTree\NodeAnalyzer\ClassChildAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;

final class OverrideFromAnonymousClassMethodAnalyzer
{
    public function __construct(
        private ClassAnalyzer $classAnalyzer,
        private NodeNameResolver $nodeNameResolver,
        private ClassChildAnalyzer $classChildAnalyzer,
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function isOverrideParentMethod(ClassLike $classLike, ClassMethod $classMethod): bool
    {
        if (! $this->classAnalyzer->isAnonymousClass($classLike)) {
            return false;
        }

        /** @var Class_ $classLike */
        if (! $classLike->extends instanceof FullyQualified) {
            return false;
        }

        $extendsClass = $classLike->extends->toString();
        if (! $this->reflectionProvider->hasClass($extendsClass)) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($extendsClass);
        $methodName = $this->nodeNameResolver->getName($classMethod);

        return $classReflection->hasMethod($methodName);
    }
}
