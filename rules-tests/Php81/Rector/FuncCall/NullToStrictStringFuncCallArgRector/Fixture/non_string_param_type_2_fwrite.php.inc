<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Fwrite
{
    public function run($stream, $data)
    {
        fwrite($stream, $data);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Fwrite
{
    public function run($stream, $data)
    {
        fwrite($stream, (string) $data);
    }
}

?>
