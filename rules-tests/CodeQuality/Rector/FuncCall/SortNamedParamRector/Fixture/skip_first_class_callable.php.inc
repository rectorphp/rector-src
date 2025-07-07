<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SortNamedParamRector\Fixture;

function run($foo = null, $bar = null, $baz = null) {}

run(...);

?>
