<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

class CallFunctionVariableHexBackreference
{
    public function run()
    {
        $result = preg_replace('/%u([a-f0-9]{4,4})/ei', 'utf8_chr(0x$1)', $_REQUEST['content']);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

class CallFunctionVariableHexBackreference
{
    public function run()
    {
        $result = preg_replace_callback('/%u([a-f0-9]{4,4})/i', function ($matches) {
            return utf8_chr(hexdec($matches[1]));
        }, $_REQUEST['content']);
    }
}

?>
