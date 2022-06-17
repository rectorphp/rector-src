<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Quotemeta
{
    public function run($string)
    {
        quotemeta($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Quotemeta
{
    public function run($string)
    {
        quotemeta((string) $string);
    }
}

?>
