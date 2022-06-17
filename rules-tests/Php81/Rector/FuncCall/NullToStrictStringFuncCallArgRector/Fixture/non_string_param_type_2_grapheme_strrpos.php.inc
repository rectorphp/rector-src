<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2GraphemeStrrpos
{
    public function run($haystack, $needle)
    {
        grapheme_strrpos($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2GraphemeStrrpos
{
    public function run($haystack, $needle)
    {
        grapheme_strrpos((string) $haystack, (string) $needle);
    }
}

?>
