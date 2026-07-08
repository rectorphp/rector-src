<?php

namespace Rector\Tests\Php56\Rector\FuncCall\PowToExpRector\Fixture;

function keepBareRightOperand($a)
{
    echo pow(2, -3);
    echo pow(2, ~3);
    echo pow(2, (int) $a);
}

?>
-----
<?php

namespace Rector\Tests\Php56\Rector\FuncCall\PowToExpRector\Fixture;

function keepBareRightOperand($a)
{
    echo 2 ** -3;
    echo 2 ** ~3;
    echo 2 ** (int) $a;
}

?>
