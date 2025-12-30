<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Source\SomeMultiArg;

$object = new SomeMultiArg();
$object->firstArgument(0, b: 1);
$object->firstArgument(a: 0);
$object->firstArgument(b: 1, a: 0);
$object->secondArgument(0, b: 1);
$object->secondArgument(b: 1);
$object->secondArgument(b: 1, a: 0);

?>

-----
<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Source\SomeMultiArg;

$object = new SomeMultiArg();
$object->firstArgument(3, b: 1);
$object->firstArgument(a: 3);
$object->firstArgument(b: 1, a: 3);
$object->secondArgument(0, b: 4);
$object->secondArgument(b: 4);
$object->secondArgument(b: 4, a: 0);

?>
