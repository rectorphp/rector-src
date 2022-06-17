<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Lcfirst
{
    public function run($string)
    {
        lcfirst($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Lcfirst
{
    public function run($string)
    {
        lcfirst((string) $string);
    }
}

?>
