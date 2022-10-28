<?php

declare(strict_types = 1);

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source;

use PhpParser\Node\Stmt\ClassLike;
use Rector\NodeNameResolver\NodeNameResolver;

class EnforceExceptionSuffixCallback
{
    public function __invoke(ClassLike $class, NodeNameResolver $nodeNameResolver): ?string {
        $fullyQualifiedClassName = (string) $nodeNameResolver->getName($class);
        if (
            // normally here would be is_subclass_of($fullyQualifiedClassName, Exception::class) condition, but it
            // would not work in the unit test, since the class itself is defined in the fixture and is not loaded
            !str_ends_with($fullyQualifiedClassName, 'Exception')
        ) {
            return $fullyQualifiedClassName . 'Exception';
        }

        return null;
    }
}
