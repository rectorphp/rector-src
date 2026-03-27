<?php

namespace Rector\Tests\Php85\Rector\Expression\NestedFuncCallsToPipeOperatorRector\Fixture;

$arrayOfArrays = [['a'], ['a'], ['b']];
$merged = array_unique(array_merge(...$arrayOfArrays));

?>
-----
<?php

namespace Rector\Tests\Php85\Rector\Expression\NestedFuncCallsToPipeOperatorRector\Fixture;

$arrayOfArrays = [['a'], ['a'], ['b']];
$merged = array_merge(...$arrayOfArrays)
    |> array_unique(...);

?>
