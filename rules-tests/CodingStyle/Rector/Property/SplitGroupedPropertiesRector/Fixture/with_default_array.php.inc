<?php

namespace Rector\Tests\CodingStyle\Rector\Property\SplitGroupedPropertiesRector\Fixture;

final class WithDefaultArray
{
    public $items, $moreItems = [];
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Property\SplitGroupedPropertiesRector\Fixture;

final class WithDefaultArray
{
    public $items;
    public $moreItems = [];
}

?>
