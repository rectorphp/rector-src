<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeMultiArg;

class StaticNamedArguments extends SomeMultiArg
{
    public function firstArgument($a = 1, $b = 2, $c = 3)
    {
        parent::firstArgument(b: 1);
        parent::firstArgument(c: 1);
        parent::firstArgument(b: 1, c: 2);
        parent::firstArgument(c: 1, b: 2);
    }

    public function secondArgument($a = 1, $b = 2, $c = 3)
    {
        parent::secondArgument(a: 1);
        parent::secondArgument(c: 1);
        parent::secondArgument(a: 1, c: 2);
        parent::secondArgument(c: 1, a: 2);
        parent::secondArgument(1, c: 2);
    }

    public function thirdArgument($a = 1, $b = 2, $c = 3)
    {
        parent::thirdArgument(a: 1);
        parent::thirdArgument(b: 1);
        parent::thirdArgument(a: 1, b: 2);
        parent::thirdArgument(b: 1, a: 2);
        parent::thirdArgument(1, b: 2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeMultiArg;

class StaticNamedArguments extends SomeMultiArg
{
    public function firstArgument($a = 1, $b = 2, $c = 3)
    {
        parent::firstArgument(b: 1, a: $a);
        parent::firstArgument(c: 1, a: $a);
        parent::firstArgument(b: 1, c: 2, a: $a);
        parent::firstArgument(c: 1, b: 2, a: $a);
    }

    public function secondArgument($a = 1, $b = 2, $c = 3)
    {
        parent::secondArgument(a: 1, b: $b);
        parent::secondArgument(c: 1, b: $b);
        parent::secondArgument(a: 1, c: 2, b: $b);
        parent::secondArgument(c: 1, a: 2, b: $b);
        parent::secondArgument(1, c: 2, b: $b);
    }

    public function thirdArgument($a = 1, $b = 2, $c = 3)
    {
        parent::thirdArgument(a: 1, c: $c);
        parent::thirdArgument(b: 1, c: $c);
        parent::thirdArgument(a: 1, b: 2, c: $c);
        parent::thirdArgument(b: 1, a: 2, c: $c);
        parent::thirdArgument(1, b: 2, c: $c);
    }
}

?>
