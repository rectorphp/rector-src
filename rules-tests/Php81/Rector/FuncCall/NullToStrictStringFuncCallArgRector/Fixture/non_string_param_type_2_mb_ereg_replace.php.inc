<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEregReplace
{
    public function run($pattern, $replacement, $string)
    {
        mb_ereg_replace($pattern, $replacement, $string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEregReplace
{
    public function run($pattern, $replacement, $string)
    {
        mb_ereg_replace((string) $pattern, (string) $replacement, (string) $string);
    }
}

?>
