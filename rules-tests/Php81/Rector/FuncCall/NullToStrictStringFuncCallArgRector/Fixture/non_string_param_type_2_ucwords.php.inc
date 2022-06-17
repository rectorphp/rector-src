<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Ucwords
{
    public function run($string, $separators)
    {
        ucwords($string, $separators);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Ucwords
{
    public function run($string, $separators)
    {
        ucwords((string) $string, $separators);
    }
}

?>
