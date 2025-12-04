<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SortNamedParamRector\Fixture;

$items = [1, 2, 3];
$result = array_map(array: $items, callback: fn ($item) => $item * 2);

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SortNamedParamRector\Fixture;

$items = [1, 2, 3];
$result = array_map(callback: fn ($item) => $item * 2, array: $items);

?>
