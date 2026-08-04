<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Source\SomeObjectWithArrayArgument;

final class CallableParam
{
    public function run(SomeObjectWithArrayArgument $someObjectWithArrayArgument)
    {
        $someObjectWithArrayArgument->withCallable([$this, 'name']);
    }

    public function name()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Source\SomeObjectWithArrayArgument;

final class CallableParam
{
    public function run(SomeObjectWithArrayArgument $someObjectWithArrayArgument)
    {
        $someObjectWithArrayArgument->withCallable($this->name(...));
    }

    public function name()
    {
    }
}

?>
