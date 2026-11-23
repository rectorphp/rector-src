<?php

namespace Rector\Tests\Php70\Rector\FuncCall\RandomFunctionRector\Fixture;

function renameSrand()
{
    srand($a);

    $a = &srand($a);

    /* foo */
    srand/** bar */
    ($a);

    a(srand());

    a(getrandmax());
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\FuncCall\RandomFunctionRector\Fixture;

function renameSrand()
{
    mt_srand($a);

    $a = &mt_srand($a);

    /* foo */
    mt_srand/** bar */
    ($a);

    a(mt_srand());

    a(mt_getrandmax());
}

?>
