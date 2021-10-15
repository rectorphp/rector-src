<?php

namespace Rector\Tests\Php73\Rector\FuncCall\RegexDashEscapeRector\Fixture;

class SkipDoubleEscapeOutsideBracket
{
    public function run(string $string)
    {
        preg_match('#^\-\s*[0-9]+$#', $string);
    }
}

?>
