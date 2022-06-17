<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2CurlEscape
{
    public function run($handle, $string)
    {
        curl_escape($handle, $string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2CurlEscape
{
    public function run($handle, $string)
    {
        curl_escape($handle, (string) $string);
    }
}

?>
