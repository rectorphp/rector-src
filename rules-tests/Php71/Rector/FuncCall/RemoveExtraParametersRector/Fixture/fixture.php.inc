<?php

namespace Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Fixture;

function removeExtraParams()
{
    strlen('asdf', 1);
    strlen('asdf');

    functionWithVariadics(1);
    functionWithVariadics(1, 2);
}

function functionWithVariadics(...$variadic)
{
    $arguments = $variadic;
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Fixture;

function removeExtraParams()
{
    strlen('asdf');
    strlen('asdf');

    functionWithVariadics(1);
    functionWithVariadics(1, 2);
}

function functionWithVariadics(...$variadic)
{
    $arguments = $variadic;
}

?>
