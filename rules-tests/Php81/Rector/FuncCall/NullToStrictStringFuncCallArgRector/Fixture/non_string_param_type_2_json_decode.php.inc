<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2JsonDecode
{
    public function run($json)
    {
        json_decode($json);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2JsonDecode
{
    public function run($json)
    {
        json_decode((string) $json);
    }
}

?>
