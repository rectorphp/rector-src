<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Htmlentities
{
    public function run($string)
    {
        htmlentities($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Htmlentities
{
    public function run($string)
    {
        htmlentities((string) $string);
    }
}

?>
