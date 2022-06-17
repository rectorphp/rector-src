<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Soundex
{
    public function run($string)
    {
        soundex($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Soundex
{
    public function run($string)
    {
        soundex((string) $string);
    }
}

?>
