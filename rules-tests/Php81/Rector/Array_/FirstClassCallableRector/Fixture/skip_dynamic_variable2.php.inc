<?php

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Fixture;

$variableName = 'x';
$index = 1;

${$variableName . $index} = call_user_func_array([], []);
