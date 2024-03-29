<?php

namespace Rector\Tests\Php80\Rector\Identical\StrStartsWithRector\Fixture;

class Strncmp
{
    public function run()
    {
        $isMatch = strncmp($haystack, $needle, strlen($needle)) == 0;

        $isMatch = 0 == strncmp($haystack, $needle, strlen($needle));
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Identical\StrStartsWithRector\Fixture;

class Strncmp
{
    public function run()
    {
        $isMatch = str_starts_with($haystack, $needle);

        $isMatch = str_starts_with($haystack, $needle);
    }
}

?>
