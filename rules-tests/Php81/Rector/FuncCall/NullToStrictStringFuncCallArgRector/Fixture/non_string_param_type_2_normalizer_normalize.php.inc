<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2NormalizerNormalize
{
    public function run($string)
    {
        normalizer_normalize($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2NormalizerNormalize
{
    public function run($string)
    {
        normalizer_normalize((string) $string);
    }
}

?>
