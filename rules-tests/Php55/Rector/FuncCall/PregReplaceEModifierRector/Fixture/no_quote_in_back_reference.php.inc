<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

class NoQuoteInBackReference
{
    public function run($str)
    {
        $str = '-159';

        echo preg_replace('~(-159)~e', 'chr(\\1)', $str);
        echo preg_replace('~(-159)~e', "chr(\\1)", $str);
        echo preg_replace('~(-159)~e', 'chr("\\1")', $str);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

class NoQuoteInBackReference
{
    public function run($str)
    {
        $str = '-159';

        echo preg_replace_callback('~(-159)~', function ($matches) {
            return chr($matches[1]);
        }, $str);
        echo preg_replace_callback('~(-159)~', function ($matches) {
            return chr($matches[1]);
        }, $str);
        echo preg_replace_callback('~(-159)~', function ($matches) {
            return chr($matches[1]);
        }, $str);
    }
}

?>
