<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2GraphemeStrripos
{
    public function run($haystack, $needle)
    {
        grapheme_strripos($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2GraphemeStrripos
{
    public function run($haystack, $needle)
    {
        grapheme_strripos((string) $haystack, (string) $needle);
    }
}

?>
