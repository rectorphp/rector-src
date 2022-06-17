<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Stripslashes
{
    public function run($string)
    {
        stripslashes($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Stripslashes
{
    public function run($string)
    {
        stripslashes((string) $string);
    }
}

?>
