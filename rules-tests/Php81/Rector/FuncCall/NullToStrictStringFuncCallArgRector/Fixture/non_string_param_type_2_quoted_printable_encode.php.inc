<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2QuotedPrintableEncode
{
    public function run($string)
    {
        quoted_printable_encode($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2QuotedPrintableEncode
{
    public function run($string)
    {
        quoted_printable_encode((string) $string);
    }
}

?>
