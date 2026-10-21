<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\NoResolveClassReflectionInForeachRule\Source;

use PhpParser\Node;

final class ReflectionResolver
{
    public function resolveClassReflection(Node $node): ?object
    {
        return null;
    }
}
