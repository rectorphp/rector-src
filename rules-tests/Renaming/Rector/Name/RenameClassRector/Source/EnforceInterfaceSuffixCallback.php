<?php

declare(strict_types = 1);

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source;

use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Interface_;
use Rector\NodeNameResolver\NodeNameResolver;

class EnforceInterfaceSuffixCallback
{
    public function __invoke(ClassLike $class, NodeNameResolver $nodeNameResolver): ?string {
        $fullyQualifiedClassName = (string) $nodeNameResolver->getName($class);
        if (
            $class instanceof Interface_ &&
            !str_ends_with($fullyQualifiedClassName, 'Interface')
        ) {
            return $fullyQualifiedClassName . 'Interface';
        }

        return null;
    }
}
