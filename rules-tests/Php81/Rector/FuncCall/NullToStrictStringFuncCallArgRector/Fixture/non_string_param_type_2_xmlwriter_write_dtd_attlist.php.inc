<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterWriteDtdAttlist
{
    public function run($writer, $name, $content)
    {
        xmlwriter_write_dtd_attlist($writer, $name, $content);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterWriteDtdAttlist
{
    public function run($writer, $name, $content)
    {
        xmlwriter_write_dtd_attlist($writer, (string) $name, (string) $content);
    }
}

?>
