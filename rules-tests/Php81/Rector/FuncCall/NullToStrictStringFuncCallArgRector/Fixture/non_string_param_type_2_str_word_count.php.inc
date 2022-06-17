<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StrWordCount
{
    public function run($string)
    {
        str_word_count($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StrWordCount
{
    public function run($string)
    {
        str_word_count((string) $string);
    }
}

?>
