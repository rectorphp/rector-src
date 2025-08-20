<?php

namespace Rector\Tests\Transform\Rector\FuncCall\WrapFuncCallWithPhpVersionIdCheckerRector\Fixture;

no_op_function();
no_op_function(1, 2);

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\FuncCall\WrapFuncCallWithPhpVersionIdCheckerRector\Fixture;

if (function_exists('no_op_function') && PHP_VERSION_ID < 80500) {
    no_op_function();
}
if (function_exists('no_op_function') && PHP_VERSION_ID < 80500) {
    no_op_function(1, 2);
}

?>
