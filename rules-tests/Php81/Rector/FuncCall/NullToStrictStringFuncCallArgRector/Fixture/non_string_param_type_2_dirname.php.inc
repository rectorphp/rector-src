<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Dirname
{
    public function run($path)
    {
        dirname($path);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Dirname
{
    public function run($path)
    {
        dirname((string) $path);
    }
}

?>
