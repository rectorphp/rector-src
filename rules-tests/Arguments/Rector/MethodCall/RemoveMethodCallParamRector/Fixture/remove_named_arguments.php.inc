<?php

namespace Rector\Tests\Arguments\Rector\MethodCall\RemoveMethodCallParamRector\Fixture;

use Rector\Tests\Arguments\Rector\MethodCall\RemoveMethodCallParamRector\Source\SomeMultiArg;

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

namespace Rector\Tests\Arguments\Rector\MethodCall\RemoveMethodCallParamRector\Fixture;

use Rector\Tests\Arguments\Rector\MethodCall\RemoveMethodCallParamRector\Source\SomeMultiArg;

$object = new SomeMultiArg();
$object->firstArgument(b: 1);
$object->firstArgument();
$object->firstArgument(b: 1);
$object->secondArgument(0);
$object->secondArgument();
$object->secondArgument(a: 0);

?>
