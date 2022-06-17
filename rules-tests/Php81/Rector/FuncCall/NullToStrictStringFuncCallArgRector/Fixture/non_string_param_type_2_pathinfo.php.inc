<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Pathinfo
{
    public function run($path)
    {
        pathinfo($path);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Pathinfo
{
    public function run($path)
    {
        pathinfo((string) $path);
    }
}

?>
