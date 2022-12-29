<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

class SubstrNotPrefix
{
    public function run()
    {
        $isNotMatch = substr($haystack, -strlen($needle)) != $needle;

        $isNotMatch = $needle != substr($haystack, -strlen($needle));
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

class SubstrNotPrefix
{
    public function run()
    {
        $isNotMatch = !str_ends_with($haystack, $needle);

        $isNotMatch = !str_ends_with($haystack, $needle);
    }
}

?>
