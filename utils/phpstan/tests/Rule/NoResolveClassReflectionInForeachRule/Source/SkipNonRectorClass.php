<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\NoResolveClassReflectionInForeachRule\Source;

final class SkipNonRectorClass
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function run(array $stmts): void
    {
        foreach ($stmts as $stmt) {
            $classReflection = $this->reflectionResolver->resolveClassReflection($stmt);
            if ($classReflection === null) {
                continue;
            }
        }
    }
}
