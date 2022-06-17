<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2IconvSubstr
{
    public function run($string, $offset)
    {
        iconv_substr($string, $offset);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2IconvSubstr
{
    public function run($string, $offset)
    {
        iconv_substr((string) $string, $offset);
    }
}

?>
