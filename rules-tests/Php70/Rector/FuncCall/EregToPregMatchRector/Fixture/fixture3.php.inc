<?php

namespace Rector\Tests\Php70\Rector\FuncCall\EregToPregMatchRector\Fixture;

function eregToPregMatch3()
{
    split('hi', 'hi, she said');

    split('hi', 'hi, she said', 0);

    spliti('hi', 'hi, she said', 1);

    split("\r\n", "line 1\r\nline 2\r\n");
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\FuncCall\EregToPregMatchRector\Fixture;

function eregToPregMatch3()
{
    preg_split('#hi#m', 'hi, she said');

    preg_split('#hi#m', 'hi, she said', 1);

    preg_split('#hi#mi', 'hi, she said', 1);

    preg_split("#\r\n#m", "line 1\r\nline 2\r\n");
}

?>
