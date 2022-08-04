<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

class NoQuoteInBackslash
{
    public function run($str)
    {
        $str = preg_replace('~&#([0-9]+);~e', 'chr(\\1)', $str);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

class NoQuoteInBackslash
{
    public function run($str)
    {
        $str = preg_replace_callback('~&#([0-9]+);~', function ($matches) {
            return chr($matches[1]);
        }, $str);
    }
}

?>
