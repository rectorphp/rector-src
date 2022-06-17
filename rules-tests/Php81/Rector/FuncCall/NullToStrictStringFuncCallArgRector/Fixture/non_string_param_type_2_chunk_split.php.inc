<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2ChunkSplit
{
    public function run($string)
    {
        chunk_split($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2ChunkSplit
{
    public function run($string)
    {
        chunk_split((string) $string);
    }
}

?>
