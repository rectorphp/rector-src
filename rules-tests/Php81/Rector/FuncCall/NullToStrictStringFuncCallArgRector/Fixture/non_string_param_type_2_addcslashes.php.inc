<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Addcslashes
{
    public function run($string, $characters)
    {
        addcslashes($string, $characters);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Addcslashes
{
    public function run($string, $characters)
    {
        addcslashes((string) $string, (string) $characters);
    }
}

?>
