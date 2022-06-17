<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2DeflateAdd
{
    public function run($context, $data, $flushMode)
    {
        deflate_add($context, $data, $flushMode);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2DeflateAdd
{
    public function run($context, $data, $flushMode)
    {
        deflate_add($context, (string) $data, $flushMode);
    }
}

?>
