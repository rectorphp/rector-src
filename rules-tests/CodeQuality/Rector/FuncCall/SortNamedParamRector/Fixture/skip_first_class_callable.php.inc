<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SortNamedParamRector\Fixture;

function skip_first_class_callable($foo = null, $bar = null, $baz = null) {}

skip_first_class_callable(...);

?>
