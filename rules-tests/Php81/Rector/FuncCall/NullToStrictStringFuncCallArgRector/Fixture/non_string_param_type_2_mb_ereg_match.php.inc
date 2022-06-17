<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEregMatch
{
    public function run($pattern, $string)
    {
        mb_ereg_match($pattern, $string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEregMatch
{
    public function run($pattern, $string)
    {
        mb_ereg_match((string) $pattern, (string) $string);
    }
}

?>
