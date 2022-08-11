<?php

namespace Rector\Tests\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector\Fixture;

use Rector\Tests\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector\Source\Options;

final class ClassExtendingArrayAccess extends Options
{
    public function offsetGet($offset)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector\Fixture;

use Rector\Tests\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector\Source\Options;

final class ClassExtendingArrayAccess extends Options
{
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
    }
}

?>
