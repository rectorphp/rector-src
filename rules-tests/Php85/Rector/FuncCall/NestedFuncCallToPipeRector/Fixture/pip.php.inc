<?php
namespace Rector\Tests\Php85\Rector\FuncCall\NestedFuncCallToPipeRector\Fixture;

$result = trim(strtoupper("Hello World"));
?>
-----
<?php
namespace Rector\Tests\Php85\Rector\FuncCall\NestedFuncCallToPipeRector\Fixture;

$result = strtoupper("Hello World") |> trim(...);
?>
