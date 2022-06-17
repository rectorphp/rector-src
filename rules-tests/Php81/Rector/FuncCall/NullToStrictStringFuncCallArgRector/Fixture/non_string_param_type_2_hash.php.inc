<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Hash
{
    public function run($algo, $data)
    {
        hash($algo, $data);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Hash
{
    public function run($algo, $data)
    {
        hash((string) $algo, (string) $data);
    }
}

?>
