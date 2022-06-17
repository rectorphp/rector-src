<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2CountChars
{
    public function run($string)
    {
        count_chars($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2CountChars
{
    public function run($string)
    {
        count_chars((string) $string);
    }
}

?>
