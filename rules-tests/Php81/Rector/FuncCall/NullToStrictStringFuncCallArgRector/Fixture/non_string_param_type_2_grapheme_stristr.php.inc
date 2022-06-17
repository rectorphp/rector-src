<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2GraphemeStristr
{
    public function run($haystack, $needle)
    {
        grapheme_stristr($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2GraphemeStristr
{
    public function run($haystack, $needle)
    {
        grapheme_stristr((string) $haystack, (string) $needle);
    }
}

?>
