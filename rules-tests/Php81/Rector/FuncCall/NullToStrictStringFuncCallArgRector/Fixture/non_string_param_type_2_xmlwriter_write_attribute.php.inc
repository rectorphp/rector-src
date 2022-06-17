<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterWriteAttribute
{
    public function run($writer, $name, $value)
    {
        xmlwriter_write_attribute($writer, $name, $value);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterWriteAttribute
{
    public function run($writer, $name, $value)
    {
        xmlwriter_write_attribute($writer, (string) $name, (string) $value);
    }
}

?>
