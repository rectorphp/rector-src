<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2ConvertUuencode
{
    public function run($string)
    {
        convert_uuencode($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2ConvertUuencode
{
    public function run($string)
    {
        convert_uuencode((string) $string);
    }
}

?>
