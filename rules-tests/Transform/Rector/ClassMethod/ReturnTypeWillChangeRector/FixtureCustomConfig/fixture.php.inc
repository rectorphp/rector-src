<?php

namespace Rector\Tests\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector\FixtureCustomConfig;

use Rector\Tests\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector\Source\ClassThatWillChangeReturnType;

final class SomeClass extends ClassThatWillChangeReturnType
{
    public function changeMyReturn()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector\FixtureCustomConfig;

use Rector\Tests\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector\Source\ClassThatWillChangeReturnType;

final class SomeClass extends ClassThatWillChangeReturnType
{
    #[\ReturnTypeWillChange]
    public function changeMyReturn()
    {
    }
}

?>
