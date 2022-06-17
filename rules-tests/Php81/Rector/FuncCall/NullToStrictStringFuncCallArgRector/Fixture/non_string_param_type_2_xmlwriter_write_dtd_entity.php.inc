<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterWriteDtdEntity
{
    public function run($writer, $name, $content)
    {
        xmlwriter_write_dtd_entity($writer, $name, $content);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterWriteDtdEntity
{
    public function run($writer, $name, $content)
    {
        xmlwriter_write_dtd_entity($writer, (string) $name, (string) $content);
    }
}

?>
