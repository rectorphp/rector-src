<?php

namespace Rector\Tests\Php80\Rector\Identical\StrStartsWithRector\Fixture;

class OtherSide
{
    public function run()
    {
        $isMatch = $needle == substr($haystack, 0, strlen($needle));

        $isNotMatch = $needle != substr($haystack, 0, strlen($needle));
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Identical\StrStartsWithRector\Fixture;

class OtherSide
{
    public function run()
    {
        $isMatch = str_starts_with($haystack, $needle);

        $isNotMatch = !str_starts_with($haystack, $needle);
    }
}

?>
