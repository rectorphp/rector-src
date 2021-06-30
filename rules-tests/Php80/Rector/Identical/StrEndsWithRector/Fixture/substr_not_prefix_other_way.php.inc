<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

class SubstrNotPrefixOtherWay
{
    public function run()
    {
        $isNotMatch = $needle !== substr($haystack, -strlen($needle));
    }
}

?>

-----
<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

class SubstrNotPrefixOtherWay
{
    public function run()
    {
        $isNotMatch = !str_ends_with($haystack, $needle);
    }
}

?>
