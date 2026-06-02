<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Source\SomeSortableObject;

class ReplaceSelfConstantInSubclass extends SomeSortableObject
{
    public function run()
    {
        $this->sortBy(self::SORT_ORDER_ASC);
    }
}

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Source\SomeSortableObject;

class ReplaceSelfConstantInSubclass extends SomeSortableObject
{
    public function run()
    {
        $this->sortBy(self::SORT_ORDER_DESC);
    }
}

?>
