<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2GraphemeStrlen
{
    public function run($string)
    {
        grapheme_strlen($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2GraphemeStrlen
{
    public function run($string)
    {
        grapheme_strlen((string) $string);
    }
}

?>
