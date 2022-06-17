<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Hebrev
{
    public function run($string)
    {
        hebrev($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Hebrev
{
    public function run($string)
    {
        hebrev((string) $string);
    }
}

?>
