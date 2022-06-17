<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterWriteCdata
{
    public function run($writer, $content)
    {
        xmlwriter_write_cdata($writer, $content);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterWriteCdata
{
    public function run($writer, $content)
    {
        xmlwriter_write_cdata($writer, (string) $content);
    }
}

?>
