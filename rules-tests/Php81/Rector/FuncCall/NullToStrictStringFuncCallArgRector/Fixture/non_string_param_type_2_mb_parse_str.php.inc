<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbParseStr
{
    public function run($string, $result)
    {
        mb_parse_str($string, $result);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbParseStr
{
    public function run($string, $result)
    {
        mb_parse_str((string) $string, $result);
    }
}

?>
