<?php

namespace Rector\Tests\Php70\Rector\FuncCall\RandomFunctionRector\Fixture;

function swapMinHigher()
{
    rand(-2, -3);
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\FuncCall\RandomFunctionRector\Fixture;

function swapMinHigher()
{
    random_int(-3, -2);
}

?>