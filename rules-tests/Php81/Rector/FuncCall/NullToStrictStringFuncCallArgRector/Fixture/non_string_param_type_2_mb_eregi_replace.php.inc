<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEregiReplace
{
    public function run($pattern, $replacement, $string)
    {
        mb_eregi_replace($pattern, $replacement, $string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEregiReplace
{
    public function run($pattern, $replacement, $string)
    {
        mb_eregi_replace((string) $pattern, (string) $replacement, (string) $string);
    }
}

?>
