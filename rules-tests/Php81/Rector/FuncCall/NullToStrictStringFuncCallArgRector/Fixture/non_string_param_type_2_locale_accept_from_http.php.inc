<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2LocaleAcceptFromHttp
{
    public function run($header)
    {
        locale_accept_from_http($header);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2LocaleAcceptFromHttp
{
    public function run($header)
    {
        locale_accept_from_http((string) $header);
    }
}

?>
