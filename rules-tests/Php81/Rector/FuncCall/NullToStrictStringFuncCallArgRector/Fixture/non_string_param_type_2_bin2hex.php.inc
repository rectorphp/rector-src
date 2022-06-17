<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bin2hex
{
    public function run($string)
    {
        bin2hex($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bin2hex
{
    public function run($string)
    {
        bin2hex((string) $string);
    }
}

?>
