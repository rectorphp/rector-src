<?php

namespace Rector\Tests\Php70\Rector\FuncCall\EregToPregMatchRector\Fixture;

function eregLiteralBrace()
{
    return eregi_replace('{[0-9]*}', '', 'a{32}b');
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\FuncCall\EregToPregMatchRector\Fixture;

function eregLiteralBrace()
{
    return preg_replace('#\{[0-9]*\}#mi', '', 'a{32}b');
}

?>
