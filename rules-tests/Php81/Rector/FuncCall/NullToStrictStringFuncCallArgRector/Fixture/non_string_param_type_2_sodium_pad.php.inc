<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumPad
{
    public function run($string, $block_size)
    {
        sodium_pad($string, $block_size);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumPad
{
    public function run($string, $block_size)
    {
        sodium_pad((string) $string, $block_size);
    }
}

?>
