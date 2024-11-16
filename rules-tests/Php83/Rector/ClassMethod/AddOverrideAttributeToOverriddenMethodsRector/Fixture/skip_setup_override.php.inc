<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipSetupOverride extends TestCase
{
    protected function setUp(): void
    {
        $result = 1000;
    }
}
