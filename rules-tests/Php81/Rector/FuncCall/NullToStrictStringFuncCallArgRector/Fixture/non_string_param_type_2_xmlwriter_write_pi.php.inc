<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterWritePi
{
    public function run($writer, $target, $content)
    {
        xmlwriter_write_pi($writer, $target, $content);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2XmlwriterWritePi
{
    public function run($writer, $target, $content)
    {
        xmlwriter_write_pi($writer, (string) $target, (string) $content);
    }
}

?>
