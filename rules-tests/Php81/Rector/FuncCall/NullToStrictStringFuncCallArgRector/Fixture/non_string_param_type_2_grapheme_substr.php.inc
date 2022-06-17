<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2GraphemeSubstr
{
    public function run($string, $offset)
    {
        grapheme_substr($string, $offset);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2GraphemeSubstr
{
    public function run($string, $offset)
    {
        grapheme_substr((string) $string, $offset);
    }
}

?>
