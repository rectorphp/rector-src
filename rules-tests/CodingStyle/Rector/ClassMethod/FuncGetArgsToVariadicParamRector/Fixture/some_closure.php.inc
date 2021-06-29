<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\FuncGetArgsToVariadicParamRector\Fixture;

function run()
{
    function () {
        $args = \func_get_args();
    };
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\FuncGetArgsToVariadicParamRector\Fixture;

function run(...$args)
{
    function (...$args) {
    };
}

?>
