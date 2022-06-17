<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strcasecmp
{
    public function run($string1, $string2)
    {
        strcasecmp($string1, $string2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strcasecmp
{
    public function run($string1, $string2)
    {
        strcasecmp((string) $string1, (string) $string2);
    }
}

?>
