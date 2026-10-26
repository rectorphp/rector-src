<?php

namespace Rector\Tests\Issues\RepeatedThreeTimes\Fixture;

use Rector\Tests\Issues\RepeatedThreeTimes\Source\SomeArrayCollection;

final class Fixture
{
    /**
     * @param array|SomeArrayCollection $items
     */
    public function run($items)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\RepeatedThreeTimes\Fixture;

use Rector\Tests\Issues\RepeatedThreeTimes\Source\SomeCollection;

final class Fixture
{
    /**
     * @param SomeCollection $items
     */
    public function run(SomeCollection $items)
    {
    }
}

?>
