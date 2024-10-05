<?php

namespace Rector\Tests\Php70\Rector\FuncCall\EregToPregMatchRector\Fixture;

function withDelimiterInMiddle($text)
{
    return (bool) ereg('Look for #anchors', $text);
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\FuncCall\EregToPregMatchRector\Fixture;

function withDelimiterInMiddle($text)
{
    return (bool) preg_match('#Look for \#anchors#m', $text);
}

?>
