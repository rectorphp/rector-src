<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Stripcslashes
{
    public function run($string)
    {
        stripcslashes($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Stripcslashes
{
    public function run($string)
    {
        stripcslashes((string) $string);
    }
}

?>
