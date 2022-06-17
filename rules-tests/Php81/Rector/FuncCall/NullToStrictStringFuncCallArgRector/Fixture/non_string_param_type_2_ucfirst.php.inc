<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Ucfirst
{
    public function run($string)
    {
        ucfirst($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Ucfirst
{
    public function run($string)
    {
        ucfirst((string) $string);
    }
}

?>
