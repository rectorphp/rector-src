<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2PregQuote
{
    public function run($str)
    {
        preg_quote($str);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2PregQuote
{
    public function run($str)
    {
        preg_quote((string) $str);
    }
}

?>
