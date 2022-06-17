<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Escapeshellarg
{
    public function run($arg)
    {
        escapeshellarg($arg);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Escapeshellarg
{
    public function run($arg)
    {
        escapeshellarg((string) $arg);
    }
}

?>
