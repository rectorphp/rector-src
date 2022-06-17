<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Base64Encode
{
    public function run($string)
    {
        base64_encode($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Base64Encode
{
    public function run($string)
    {
        base64_encode((string) $string);
    }
}

?>
