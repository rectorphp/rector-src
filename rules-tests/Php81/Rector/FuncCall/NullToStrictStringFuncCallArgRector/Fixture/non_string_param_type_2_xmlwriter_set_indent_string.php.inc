<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterSetIndentString
{
    public function run($writer, $indentation)
    {
        xmlwriter_set_indent_string($writer, $indentation);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterSetIndentString
{
    public function run($writer, $indentation)
    {
        xmlwriter_set_indent_string($writer, (string) $indentation);
    }
}

?>
