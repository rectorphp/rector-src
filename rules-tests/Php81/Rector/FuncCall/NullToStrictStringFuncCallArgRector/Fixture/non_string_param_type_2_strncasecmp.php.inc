<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strncasecmp
{
    public function run($string1, $string2, $length)
    {
        strncasecmp($string1, $string2, $length);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strncasecmp
{
    public function run($string1, $string2, $length)
    {
        strncasecmp((string) $string1, (string) $string2, $length);
    }
}

?>
