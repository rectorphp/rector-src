<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterWriteDtdElement
{
    public function run($writer, $name, $content)
    {
        xmlwriter_write_dtd_element($writer, $name, $content);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterWriteDtdElement
{
    public function run($writer, $name, $content)
    {
        xmlwriter_write_dtd_element($writer, (string) $name, (string) $content);
    }
}

?>
