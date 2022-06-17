<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2CurlUnescape
{
    public function run($handle, $string)
    {
        curl_unescape($handle, $string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2CurlUnescape
{
    public function run($handle, $string)
    {
        curl_unescape($handle, (string) $string);
    }
}

?>
