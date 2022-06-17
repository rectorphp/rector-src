<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Gzcompress
{
    public function run($data, $level, $encoding)
    {
        gzcompress($data, $level, $encoding);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Gzcompress
{
    public function run($data, $level, $encoding)
    {
        gzcompress((string) $data, $level, $encoding);
    }
}

?>
