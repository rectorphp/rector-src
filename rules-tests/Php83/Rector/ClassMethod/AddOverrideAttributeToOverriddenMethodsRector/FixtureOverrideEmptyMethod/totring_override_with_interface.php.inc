<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureOverrideEmptyMethod;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromInterface;
use Stringable;

final class ToStringOverrideWithInterface implements Stringable, ExampleFromInterface
{
    public function __toString(): string
    {
        return 'hello';
    }

    public function foo()
    {
        return 'implements only';
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureOverrideEmptyMethod;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromInterface;
use Stringable;

final class ToStringOverrideWithInterface implements Stringable, ExampleFromInterface
{
    #[\Override]
    public function __toString(): string
    {
        return 'hello';
    }

    public function foo()
    {
        return 'implements only';
    }
}

?>
