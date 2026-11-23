<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

class Fixture
{
    /**
     * @param non-empty-string $needle
     */
    public function run(string $haystack, string $needle)
    {
        $isMatch = substr($haystack, -strlen($needle)) === $needle;

        $isMatch = $needle === substr($haystack, -strlen($needle));
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

class Fixture
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
