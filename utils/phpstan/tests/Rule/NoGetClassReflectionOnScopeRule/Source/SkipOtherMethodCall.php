<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\NoGetClassReflectionOnScopeRule\Source;

use PHPStan\Analyser\Scope;

final class SkipOtherMethodCall
{
    public function run(Scope $scope): void
    {
        $isInClass = $scope->isInClass();
    }
}
