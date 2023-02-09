<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

class DirectDollarNumber
{
    public function run()
    {
        $str = '&#123;';
        echo preg_replace('/&#(\\d+);/e', 'strlen($1)', utf8_encode($str));
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

class DirectDollarNumber
{
    public function run()
    {
        $str = '&#123;';
        echo preg_replace_callback('/&#(\d+);/', function ($matches) {
            return strlen($matches[1]);
        }, utf8_encode($str));
    }
}

?>
