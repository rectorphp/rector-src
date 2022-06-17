<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strpbrk
{
    public function run($string, $characters)
    {
        strpbrk($string, $characters);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strpbrk
{
    public function run($string, $characters)
    {
        strpbrk((string) $string, (string) $characters);
    }
}

?>
