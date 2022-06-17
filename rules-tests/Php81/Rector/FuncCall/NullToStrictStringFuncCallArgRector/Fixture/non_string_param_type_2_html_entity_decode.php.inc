<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2HtmlEntityDecode
{
    public function run($string)
    {
        html_entity_decode($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2HtmlEntityDecode
{
    public function run($string)
    {
        html_entity_decode((string) $string);
    }
}

?>
