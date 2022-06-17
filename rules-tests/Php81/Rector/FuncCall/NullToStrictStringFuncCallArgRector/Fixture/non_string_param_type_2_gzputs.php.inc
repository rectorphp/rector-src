<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Gzputs
{
    public function run($stream, $data, $length)
    {
        gzputs($stream, $data, $length);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Gzputs
{
    public function run($stream, $data, $length)
    {
        gzputs($stream, (string) $data, $length);
    }
}

?>
