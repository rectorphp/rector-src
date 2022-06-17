<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StrGetcsv
{
    public function run($string)
    {
        str_getcsv($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StrGetcsv
{
    public function run($string)
    {
        str_getcsv((string) $string);
    }
}

?>
