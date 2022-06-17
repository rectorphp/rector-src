<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2InflateAdd
{
    public function run($context, $data, $flushMode)
    {
        inflate_add($context, $data, $flushMode);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2InflateAdd
{
    public function run($context, $data, $flushMode)
    {
        inflate_add($context, (string) $data, $flushMode);
    }
}

?>
