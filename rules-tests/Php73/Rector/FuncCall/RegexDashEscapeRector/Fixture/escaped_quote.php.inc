<?php

namespace Rector\Tests\Php73\Rector\FuncCall\RegexDashEscapeRector\Fixture;

function escapedQuote()
{
    $string = '...';
    $string = preg_replace('/[^[:alnum:]\s-\'\/]+/', '', $string);
    $string = preg_replace('/\'[^[:alnum:]\s-\'\/]+/', '', $string);
}

?>
-----
<?php

namespace Rector\Tests\Php73\Rector\FuncCall\RegexDashEscapeRector\Fixture;

function escapedQuote()
{
    $string = '...';
    $string = preg_replace('/[^[:alnum:]\s\-\'\/]+/', '', $string);
    $string = preg_replace('/\'[^[:alnum:]\s\-\'\/]+/', '', $string);
}

?>
