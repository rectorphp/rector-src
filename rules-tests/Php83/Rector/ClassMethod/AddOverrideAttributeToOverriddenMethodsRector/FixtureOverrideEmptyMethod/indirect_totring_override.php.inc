<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureOverrideEmptyMethod;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\SomeClassImplementsToString;

final class IndirectToStringOverride extends SomeClassImplementsToString
{
    public function __toString(): string
    {
        return 'hello';
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureOverrideEmptyMethod;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\SomeClassImplementsToString;

final class IndirectToStringOverride extends SomeClassImplementsToString
{
    #[\Override]
    public function __toString(): string
    {
        return 'hello';
    }
}

?>
