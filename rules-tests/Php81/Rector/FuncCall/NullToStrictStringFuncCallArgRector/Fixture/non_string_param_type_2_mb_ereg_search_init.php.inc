<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEregSearchInit
{
    public function run($string)
    {
        mb_ereg_search_init($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEregSearchInit
{
    public function run($string)
    {
        mb_ereg_search_init((string) $string);
    }
}

?>
