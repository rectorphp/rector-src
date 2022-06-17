<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MysqliEscapeString
{
    public function run($mysql, $string)
    {
        mysqli_escape_string($mysql, $string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MysqliEscapeString
{
    public function run($mysql, $string)
    {
        mysqli_escape_string($mysql, (string) $string);
    }
}

?>
