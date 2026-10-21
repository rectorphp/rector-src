<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\NoResolveClassReflectionInForeachRule\Source;

use Rector\Contract\Rector\RectorInterface;

final class SkipCallOutsideForeach implements RectorInterface
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function run(array $stmts): void
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($stmts[0]);
        if ($classReflection === null) {
            return;
        }

        foreach ($stmts as $stmt) {
            // reflection already resolved above the loop
        }
    }
}
