<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Gzwrite
{
    public function run($stream, $data, $length)
    {
        gzwrite($stream, $data, $length);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Gzwrite
{
    public function run($stream, $data, $length)
    {
        gzwrite($stream, (string) $data, $length);
    }
}

?>
