<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2ParseUrl
{
    public function run($url)
    {
        parse_url($url);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2ParseUrl
{
    public function run($url)
    {
        parse_url((string) $url);
    }
}

?>
