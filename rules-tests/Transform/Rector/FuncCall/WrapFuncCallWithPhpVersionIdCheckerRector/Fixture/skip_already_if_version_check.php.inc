<?php

namespace Rector\Tests\Transform\Rector\FuncCall\WrapFuncCallWithPhpVersionIdCheckerRector\Fixture;

if (PHP_VERSION_ID < 80500) {
    no_op_function();
}
if (PHP_VERSION_ID < 80500) {
    no_op_function(1, 2);
}

?>
