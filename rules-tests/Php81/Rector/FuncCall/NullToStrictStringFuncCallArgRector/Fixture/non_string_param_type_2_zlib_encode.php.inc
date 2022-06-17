<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2ZlibEncode
{
    public function run($data, $encoding, $level)
    {
        zlib_encode($data, $encoding, $level);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2ZlibEncode
{
    public function run($data, $encoding, $level)
    {
        zlib_encode((string) $data, $encoding, $level);
    }
}

?>
