<?php
namespace Rector\Tests\Php73\Rector\FuncCall\RegexDashEscapeRector\Fixture;

class SkipDoubleEscapeWord
{
    public function run(string $string)
    {
        preg_match('/[\-\w]/', $string);
    }
}


?>
