<?php

namespace Rector\Tests\Php56\Rector\FuncCall\PowToExpRector\Fixture;

function lowerPrecedenceOperands($a)
{
    echo pow(~3, 4);
    echo pow(!3, 4);
    echo pow((int) "5x", 2);
    echo pow($a += 4, 3);
    echo pow($a ? 4 : 3, 2);
    echo pow(2, $a ? 3 : 4);
    echo pow(2, $a = 4);
}

?>
-----
<?php

namespace Rector\Tests\Php56\Rector\FuncCall\PowToExpRector\Fixture;

function lowerPrecedenceOperands($a)
{
    echo (~3) ** 4;
    echo (!3) ** 4;
    echo ((int) "5x") ** 2;
    echo ($a += 4) ** 3;
    echo ($a ? 4 : 3) ** 2;
    echo 2 ** ($a ? 3 : 4);
    echo 2 ** ($a = 4);
}

?>
