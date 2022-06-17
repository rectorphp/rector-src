<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2NormalizerGetRawDecomposition
{
    public function run($string)
    {
        normalizer_get_raw_decomposition($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2NormalizerGetRawDecomposition
{
    public function run($string)
    {
        normalizer_get_raw_decomposition((string) $string);
    }
}

?>
