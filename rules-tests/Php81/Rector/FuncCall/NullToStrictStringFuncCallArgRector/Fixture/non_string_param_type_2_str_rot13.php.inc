<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StrRot13
{
    public function run($string)
    {
        str_rot13($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StrRot13
{
    public function run($string)
    {
        str_rot13((string) $string);
    }
}

?>
