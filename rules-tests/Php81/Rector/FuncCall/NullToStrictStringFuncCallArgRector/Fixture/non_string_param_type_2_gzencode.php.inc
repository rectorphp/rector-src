<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Gzencode
{
    public function run($data, $level, $encoding)
    {
        gzencode($data, $level, $encoding);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Gzencode
{
    public function run($data, $level, $encoding)
    {
        gzencode((string) $data, $level, $encoding);
    }
}

?>
