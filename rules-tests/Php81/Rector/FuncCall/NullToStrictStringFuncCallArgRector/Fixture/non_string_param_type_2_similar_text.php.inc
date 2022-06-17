<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SimilarText
{
    public function run($string1, $string2)
    {
        similar_text($string1, $string2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SimilarText
{
    public function run($string1, $string2)
    {
        similar_text((string) $string1, (string) $string2);
    }
}

?>
