<?php

namespace Rector\Tests\Arguments\Rector\MethodCall\RemoveMethodCallParamRector\Fixture;

use Rector\Tests\Arguments\Rector\MethodCall\RemoveMethodCallParamRector\Source\SomeMultiArg;

$object = new SomeMultiArg();
$object->firstArgument(b: 1);
$object->secondArgument(a: 1);
$object->secondArgument(0, c: 2);
