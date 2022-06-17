<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2GraphemeStrpos
{
    public function run($haystack, $needle)
    {
        grapheme_strpos($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2GraphemeStrpos
{
    public function run($haystack, $needle)
    {
        grapheme_strpos((string) $haystack, (string) $needle);
    }
}

?>
