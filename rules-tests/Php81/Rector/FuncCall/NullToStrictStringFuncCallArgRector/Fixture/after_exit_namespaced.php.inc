<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

exit();

echo 'some statement';

function run2()
{
    preg_split('#a#', null);
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

exit();

echo 'some statement';

function run2()
{
    preg_split('#a#', '');
}

?>
