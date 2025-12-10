<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

$variableName = 'x';
$index = 1;

${$variableName . $index} = call_user_func_array([], []);
