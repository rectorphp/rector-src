<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeMultiArg;

class NamedArguments
{
    public function run()
    {
        $containerBuilder = new SomeMultiArg();
        $containerBuilder->firstArgument(b: 1);
        $containerBuilder->firstArgument(c: 1);
        $containerBuilder->firstArgument(b: 1, c: 2);
        $containerBuilder->firstArgument(c: 1, b: 2);
        $containerBuilder->secondArgument(a: 1);
        $containerBuilder->secondArgument(c: 1);
        $containerBuilder->secondArgument(a: 1, c: 2);
        $containerBuilder->secondArgument(c: 1, a: 2);
        $containerBuilder->secondArgument(1, c: 2);
        $containerBuilder->thirdArgument(a: 1);
        $containerBuilder->thirdArgument(b: 1);
        $containerBuilder->thirdArgument(a: 1, b: 2);
        $containerBuilder->thirdArgument(b: 1, a: 2);
        $containerBuilder->thirdArgument(1, b: 2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeMultiArg;

class NamedArguments
{
    public function run()
    {
        $containerBuilder = new SomeMultiArg();
        $containerBuilder->firstArgument(b: 1, a: 4);
        $containerBuilder->firstArgument(c: 1, a: 4);
        $containerBuilder->firstArgument(b: 1, c: 2, a: 4);
        $containerBuilder->firstArgument(c: 1, b: 2, a: 4);
        $containerBuilder->secondArgument(a: 1, b: 5);
        $containerBuilder->secondArgument(c: 1, b: 5);
        $containerBuilder->secondArgument(a: 1, c: 2, b: 5);
        $containerBuilder->secondArgument(c: 1, a: 2, b: 5);
        $containerBuilder->secondArgument(1, c: 2, b: 5);
        $containerBuilder->thirdArgument(a: 1, c: 6);
        $containerBuilder->thirdArgument(b: 1, c: 6);
        $containerBuilder->thirdArgument(a: 1, b: 2, c: 6);
        $containerBuilder->thirdArgument(b: 1, a: 2, c: 6);
        $containerBuilder->thirdArgument(1, b: 2, c: 6);
    }
}

?>
