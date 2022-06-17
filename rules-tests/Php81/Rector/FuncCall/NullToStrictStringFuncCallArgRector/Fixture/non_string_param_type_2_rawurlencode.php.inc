<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Rawurlencode
{
    public function run($string)
    {
        rawurlencode($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Rawurlencode
{
    public function run($string)
    {
        rawurlencode((string) $string);
    }
}

?>
