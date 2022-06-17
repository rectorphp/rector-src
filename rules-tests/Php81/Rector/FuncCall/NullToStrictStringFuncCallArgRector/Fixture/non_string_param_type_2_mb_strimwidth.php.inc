<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrimwidth
{
    public function run($string, $start, $width, $trimMarker)
    {
        mb_strimwidth($string, $start, $width, $trimMarker);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrimwidth
{
    public function run($string, $start, $width, $trimMarker)
    {
        mb_strimwidth((string) $string, $start, $width, (string) $trimMarker);
    }
}

?>
