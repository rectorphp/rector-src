<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Gzdeflate
{
    public function run($data, $level, $encoding)
    {
        gzdeflate($data, $level, $encoding);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Gzdeflate
{
    public function run($data, $level, $encoding)
    {
        gzdeflate((string) $data, $level, $encoding);
    }
}

?>
