<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2HashUpdate
{
    public function run($context, $data)
    {
        hash_update($context, $data);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2HashUpdate
{
    public function run($context, $data)
    {
        hash_update($context, (string) $data);
    }
}

?>
