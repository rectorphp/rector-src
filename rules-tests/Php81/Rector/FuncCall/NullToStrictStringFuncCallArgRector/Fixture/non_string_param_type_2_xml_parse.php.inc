<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlParse
{
    public function run($parse, $data)
    {
        xml_parse($parse, $data);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlParse
{
    public function run($parse, $data)
    {
        xml_parse($parse, (string) $data);
    }
}

?>
