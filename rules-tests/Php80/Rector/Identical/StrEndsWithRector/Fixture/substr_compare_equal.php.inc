<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

class SubstrCompare
{
    /**
     * @param non-empty-string $needle
     */
    public function run(string $haystack, string $needle)
    {
        $isMatch = substr_compare($haystack, $needle, -strlen($needle)) == 0;

        $isMatch = 0 == substr_compare($haystack, $needle, -strlen($needle));
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

class SubstrCompare
{
    /**
     * @param non-empty-string $needle
     */
    public function run(string $haystack, string $needle)
    {
        $isMatch = str_ends_with($haystack, $needle);

        $isMatch = str_ends_with($haystack, $needle);
    }
}

?>
