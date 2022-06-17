<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strnatcmp
{
    public function run($string1, $string2)
    {
        strnatcmp($string1, $string2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strnatcmp
{
    public function run($string1, $string2)
    {
        strnatcmp((string) $string1, (string) $string2);
    }
}

?>
