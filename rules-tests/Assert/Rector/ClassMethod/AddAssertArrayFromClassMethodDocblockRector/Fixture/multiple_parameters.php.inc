<?php

namespace Rector\Tests\Assert\Rector\ClassMethod\AddAssertArrayFromClassMethodDocblockRector\Fixture;

final class MultipleParameters
{
    /**
     * @param array<string, string> $items
     * @param string[] $names
     */
    public function run(array $items, array $names)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Assert\Rector\ClassMethod\AddAssertArrayFromClassMethodDocblockRector\Fixture;

final class MultipleParameters
{
    /**
     * @param array<string, string> $items
     * @param string[] $names
     */
    public function run(array $items, array $names)
    {
        \Webmozart\Assert\Assert::allString($items);
        \Webmozart\Assert\Assert::allString(array_keys($items));
        \Webmozart\Assert\Assert::allString($names);
    }
}

?>
