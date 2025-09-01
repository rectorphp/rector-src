<?php

namespace Rector\Tests\Assert\Rector\ClassMethod\AddAssertArrayFromClassMethodDocblockRector\Fixture;

final class SimpleArray
{
    /**
     * @param int[] $items
     */
    public function run(array $items)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Assert\Rector\ClassMethod\AddAssertArrayFromClassMethodDocblockRector\Fixture;

final class SimpleArray
{
    /**
     * @param int[] $items
     */
    public function run(array $items)
    {
        \Webmozart\Assert\Assert::allInteger($items);
    }
}

?>
