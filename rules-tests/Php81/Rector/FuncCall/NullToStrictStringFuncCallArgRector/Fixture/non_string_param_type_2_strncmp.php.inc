<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strncmp
{
    public function run($string1, $string2, $length)
    {
        strncmp($string1, $string2, $length);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strncmp
{
    public function run($string1, $string2, $length)
    {
        strncmp((string) $string1, (string) $string2, $length);
    }
}

?>
