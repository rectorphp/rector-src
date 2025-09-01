<?php

namespace Rector\Tests\Assert\Rector\ClassMethod\AddAssertArrayFromClassMethodDocblockRector\Fixture;

final class FloatIntBool
{
    /**
     * @param int[] $items
     * @param float[] $numbers
     * @param bool[] $options
     */
    public function run(array $items, array $numbers, array $options)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Assert\Rector\ClassMethod\AddAssertArrayFromClassMethodDocblockRector\Fixture;

final class FloatIntBool
{
    /**
     * @param int[] $items
     * @param float[] $numbers
     * @param bool[] $options
     */
    public function run(array $items, array $numbers, array $options)
    {
        \Webmozart\Assert\Assert::allInteger($items);
        \Webmozart\Assert\Assert::allFloat($numbers);
        \Webmozart\Assert\Assert::allBoolean($options);
    }
}

?>
