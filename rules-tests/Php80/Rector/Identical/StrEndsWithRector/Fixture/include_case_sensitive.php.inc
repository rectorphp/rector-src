<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

final class IncludeCaseSensitive
{
    /**
     * @param non-empty-string $needle
     */
    public function run($haystack, $needle)
    {
        return substr_compare($haystack, $needle, -strlen($needle), null, false) === 0;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

final class IncludeCaseSensitive
{
    /**
     * @param non-empty-string $needle
     */
    public function run($haystack, $needle)
    {
        return str_ends_with($haystack, $needle);
    }
}

?>
