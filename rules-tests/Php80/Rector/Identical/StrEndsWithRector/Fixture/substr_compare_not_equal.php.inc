<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

class SubstrCompareNot
{
    /**
     * @param non-empty-string $needle
     */
    public function run(string $haystack, string $needle)
    {
        $isNotMatch = substr_compare($haystack, $needle, -strlen($needle)) != 0;

        $isNotMatch = 0 != substr_compare($haystack, $needle, -strlen($needle));
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

class SubstrCompareNot
{
    /**
     * @param non-empty-string $needle
     */
    public function run(string $haystack, string $needle)
    {
        $isNotMatch = !str_ends_with($haystack, $needle);

        $isNotMatch = !str_ends_with($haystack, $needle);
    }
}

?>
