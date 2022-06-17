<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbSubstr
{
    public function run($string, $start)
    {
        mb_substr($string, $start);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbSubstr
{
    public function run($string, $start)
    {
        mb_substr((string) $string, $start);
    }
}

?>
