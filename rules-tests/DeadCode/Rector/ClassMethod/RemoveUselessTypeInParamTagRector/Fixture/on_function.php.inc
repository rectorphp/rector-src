<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

/**
 * @param string $a
 */
function foo(string $a)
{
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

/**
 * @param $a
 */
function foo(string $a)
{
}

?>
