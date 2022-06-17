<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2NormalizerIsNormalized
{
    public function run($string)
    {
        normalizer_is_normalized($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2NormalizerIsNormalized
{
    public function run($string)
    {
        normalizer_is_normalized((string) $string);
    }
}

?>
