<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SimplexmlLoadString
{
    public function run($data)
    {
        simplexml_load_string($data);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SimplexmlLoadString
{
    public function run($data)
    {
        simplexml_load_string((string) $data);
    }
}

?>
