<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlParseIntoStruct
{
    public function run($parse, $data, $values)
    {
        xml_parse_into_struct($parse, $data, $values);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlParseIntoStruct
{
    public function run($parse, $data, $values)
    {
        xml_parse_into_struct($parse, (string) $data, $values);
    }
}

?>
