<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Source\SomeMultiArg;

use Symfony\Component\Yaml\Yaml;

$object = new SomeMultiArg();
$object->firstArgument(b: 1);
$object->secondArgument(a: 1);
$object->secondArgument(0, c: 2);
Yaml::parse('...', flags: false, other: false, another: true);
