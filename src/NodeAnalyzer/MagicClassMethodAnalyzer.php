<?php

declare(strict_types=1);

namespace Rector\NodeAnalyzer;

use PhpParser\Node\Stmt\ClassMethod;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\ValueObject\MethodName;

final readonly class MagicClassMethodAnalyzer
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    public function isUnsafeOverridden(ClassMethod $classMethod): bool
    {
        if ($this->nodeNameResolver->isName($classMethod, MethodName::INVOKE)) {
            return false;
        }

        return $classMethod->isMagic();
    }
}
