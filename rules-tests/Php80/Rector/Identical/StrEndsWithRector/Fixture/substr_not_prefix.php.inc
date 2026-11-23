<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

class SubstrNotPrefix
{
    /**
     * @param non-empty-string $needle
     */
    public function run(string $haystack, string $needle)
    {
        $isNotMatch = substr($haystack, -strlen($needle)) !== $needle;

        $isNotMatch = $needle !== substr($haystack, -strlen($needle));
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

class SubstrNotPrefix
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
