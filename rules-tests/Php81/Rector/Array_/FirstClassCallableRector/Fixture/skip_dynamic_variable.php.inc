<?php

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Fixture;

$variableName = 'x';
${$variableName} = call_user_func_array([], []);
