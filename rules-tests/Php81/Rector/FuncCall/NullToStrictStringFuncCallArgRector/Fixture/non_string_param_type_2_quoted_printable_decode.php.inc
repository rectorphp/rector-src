<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2QuotedPrintableDecode
{
    public function run($string)
    {
        quoted_printable_decode($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2QuotedPrintableDecode
{
    public function run($string)
    {
        quoted_printable_decode((string) $string);
    }
}

?>
