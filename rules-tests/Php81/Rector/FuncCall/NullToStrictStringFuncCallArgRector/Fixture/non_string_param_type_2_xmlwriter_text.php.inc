<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterText
{
    public function run($writer, $content)
    {
        xmlwriter_text($writer, $content);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterText
{
    public function run($writer, $content)
    {
        xmlwriter_text($writer, (string) $content);
    }
}

?>
