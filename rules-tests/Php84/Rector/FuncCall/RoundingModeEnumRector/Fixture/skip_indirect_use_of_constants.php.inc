<?php

namespace Rector\Tests\Php84\Rector\FuncCall\RoundingModeEnumRector;


$mode = PHP_ROUND_HALF_DOWN;

round(1.5, 0, $mode);

?>
