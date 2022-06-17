<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2GraphemeStripos
{
    public function run($haystack, $needle)
    {
        grapheme_stripos($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2GraphemeStripos
{
    public function run($haystack, $needle)
    {
        grapheme_stripos((string) $haystack, (string) $needle);
    }
}

?>
