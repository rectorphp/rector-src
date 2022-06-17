<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Rawurldecode
{
    public function run($string)
    {
        rawurldecode($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Rawurldecode
{
    public function run($string)
    {
        rawurldecode((string) $string);
    }
}

?>
