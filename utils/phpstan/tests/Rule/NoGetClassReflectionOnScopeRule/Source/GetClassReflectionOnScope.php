<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\NoGetClassReflectionOnScopeRule\Source;

use PHPStan\Analyser\Scope;

final class GetClassReflectionOnScope
{
    public function run(Scope $scope): void
    {
        $classReflection = $scope->getClassReflection();
    }
}
