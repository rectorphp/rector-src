<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

exit();

echo 'some statement';

function run2()
{
    preg_split('#a#', null);
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

exit();

echo 'some statement';

function run2()
{
    preg_split('#a#', '');
}

?>
