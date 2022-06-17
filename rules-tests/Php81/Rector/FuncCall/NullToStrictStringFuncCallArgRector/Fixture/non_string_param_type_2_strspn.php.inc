<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strspn
{
    public function run($string, $characters)
    {
        strspn($string, $characters);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strspn
{
    public function run($string, $characters)
    {
        strspn((string) $string, (string) $characters);
    }
}

?>
