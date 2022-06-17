<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2HtmlspecialcharsDecode
{
    public function run($string)
    {
        htmlspecialchars_decode($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2HtmlspecialcharsDecode
{
    public function run($string)
    {
        htmlspecialchars_decode((string) $string);
    }
}

?>
