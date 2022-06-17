<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MsgfmtParse
{
    public function run($formatter, $string)
    {
        msgfmt_parse($formatter, $string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MsgfmtParse
{
    public function run($formatter, $string)
    {
        msgfmt_parse($formatter, (string) $string);
    }
}

?>
