<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2GraphemeStrstr
{
    public function run($haystack, $needle)
    {
        grapheme_strstr($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2GraphemeStrstr
{
    public function run($haystack, $needle)
    {
        grapheme_strstr((string) $haystack, (string) $needle);
    }
}

?>
