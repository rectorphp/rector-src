<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterWriteRaw
{
    public function run($writer, $content)
    {
        xmlwriter_write_raw($writer, $content);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterWriteRaw
{
    public function run($writer, $content)
    {
        xmlwriter_write_raw($writer, (string) $content);
    }
}

?>
