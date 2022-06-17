<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strnatcasecmp
{
    public function run($string1, $string2)
    {
        strnatcasecmp($string1, $string2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strnatcasecmp
{
    public function run($string1, $string2)
    {
        strnatcasecmp((string) $string1, (string) $string2);
    }
}

?>
