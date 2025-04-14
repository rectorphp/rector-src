<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureOverrideEmptyMethod;

use PHPUnit\Framework\TestCase;

final class SetupOverride extends TestCase
{
    protected function setUp(): void
    {
        $result = 1000;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureOverrideEmptyMethod;

use PHPUnit\Framework\TestCase;

final class SetupOverride extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $result = 1000;
    }
}

?>
