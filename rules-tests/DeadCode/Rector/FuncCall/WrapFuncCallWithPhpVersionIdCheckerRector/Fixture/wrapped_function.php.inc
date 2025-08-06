<?php

namespace Rector\Tests\DeadCode\Rector\FuncCall\WrapFuncCallWithPhpVersionIdCheckerRector\Fixture;

no_op_function();
no_op_function(1, 2);

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FuncCall\WrapFuncCallWithPhpVersionIdCheckerRector\Fixture;

if (PHP_VERSION_ID < 80500) {
    no_op_function();
}
if (PHP_VERSION_ID < 80500) {
    no_op_function(1, 2);
}

?>
