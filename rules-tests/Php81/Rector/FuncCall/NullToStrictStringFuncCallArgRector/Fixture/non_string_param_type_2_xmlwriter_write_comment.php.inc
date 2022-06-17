<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterWriteComment
{
    public function run($writer, $content)
    {
        xmlwriter_write_comment($writer, $content);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterWriteComment
{
    public function run($writer, $content)
    {
        xmlwriter_write_comment($writer, (string) $content);
    }
}

?>
