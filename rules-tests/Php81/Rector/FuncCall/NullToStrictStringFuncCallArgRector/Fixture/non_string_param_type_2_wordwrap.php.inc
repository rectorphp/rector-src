<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Wordwrap
{
    public function run($string)
    {
        wordwrap($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Wordwrap
{
    public function run($string)
    {
        wordwrap((string) $string);
    }
}

?>
