<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Unpack
{
    public function run($format, $string)
    {
        unpack($format, $string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Unpack
{
    public function run($format, $string)
    {
        unpack((string) $format, (string) $string);
    }
}

?>
