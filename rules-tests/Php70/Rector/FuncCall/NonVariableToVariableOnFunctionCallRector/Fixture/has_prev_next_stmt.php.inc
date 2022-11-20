<?php

namespace Rector\Tests\Php70\Rector\FuncCall\NonVariableToVariableOnFunctionCallRector\Fixture;

function hasPrevNextStmt()
{
    echo 'prev stmt';
    reset($var = []);
    echo 'next stmt';
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\FuncCall\NonVariableToVariableOnFunctionCallRector\Fixture;

function hasPrevNextStmt()
{
    echo 'prev stmt';
    $var = [];
    reset($var);
    echo 'next stmt';
}

?>
