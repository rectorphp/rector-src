<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MsgfmtParseMessage
{
    public function run($locale, $pattern, $message)
    {
        msgfmt_parse_message($locale, $pattern, $message);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MsgfmtParseMessage
{
    public function run($locale, $pattern, $message)
    {
        msgfmt_parse_message((string) $locale, (string) $pattern, (string) $message);
    }
}

?>
