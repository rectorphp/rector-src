<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strcspn
{
    public function run($string, $characters)
    {
        strcspn($string, $characters);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strcspn
{
    public function run($string, $characters)
    {
        strcspn((string) $string, (string) $characters);
    }
}

?>
