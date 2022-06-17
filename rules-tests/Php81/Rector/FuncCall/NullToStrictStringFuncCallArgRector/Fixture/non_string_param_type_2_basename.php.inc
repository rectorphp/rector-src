<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Basename
{
    public function run($path, $suffix)
    {
        basename($path, $suffix);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Basename
{
    public function run($path, $suffix)
    {
        basename((string) $path, $suffix);
    }
}

?>
