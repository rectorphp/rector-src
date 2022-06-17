<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bcsqrt
{
    public function run($num)
    {
        bcsqrt($num);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bcsqrt
{
    public function run($num)
    {
        bcsqrt((string) $num);
    }
}

?>
