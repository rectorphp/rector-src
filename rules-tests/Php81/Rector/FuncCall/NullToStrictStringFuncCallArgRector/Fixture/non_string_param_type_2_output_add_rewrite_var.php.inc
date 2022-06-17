<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2OutputAddRewriteVar
{
    public function run($name, $value)
    {
        output_add_rewrite_var($name, $value);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2OutputAddRewriteVar
{
    public function run($name, $value)
    {
        output_add_rewrite_var((string) $name, (string) $value);
    }
}

?>
