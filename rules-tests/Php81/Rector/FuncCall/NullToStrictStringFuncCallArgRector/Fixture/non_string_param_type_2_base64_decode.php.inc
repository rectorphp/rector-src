<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Base64Decode
{
    public function run($string)
    {
        base64_decode($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Base64Decode
{
    public function run($string)
    {
        base64_decode((string) $string);
    }
}

?>
