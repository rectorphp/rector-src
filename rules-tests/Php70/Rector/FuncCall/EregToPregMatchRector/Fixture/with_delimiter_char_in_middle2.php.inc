<?php

namespace Rector\Tests\Php70\Rector\FuncCall\EregToPregMatchRector\Fixture;

function withDelimiterInMiddle2($text)
{
    return (bool) ereg('A# (minor|major)', $text);
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\FuncCall\EregToPregMatchRector\Fixture;

function withDelimiterInMiddle2($text)
{
    return (bool) preg_match('#A\# (minor|major)#m', $text);
}

?>
