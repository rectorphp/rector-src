<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Fputs
{
    public function run($stream, $data)
    {
        fputs($stream, $data);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Fputs
{
    public function run($stream, $data)
    {
        fputs($stream, (string) $data);
    }
}

?>
