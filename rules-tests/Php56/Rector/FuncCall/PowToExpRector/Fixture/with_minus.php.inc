<?php

namespace Rector\Tests\Php56\Rector\FuncCall\PowToExpRector\Fixture;

function withMinus()
{
    echo pow(-2, 3);
    echo pow(-2, 2);
}

?>
-----
<?php

namespace Rector\Tests\Php56\Rector\FuncCall\PowToExpRector\Fixture;

function withMinus()
{
    echo (-2) ** 3;
    echo (-2) ** 2;
}

?>
