<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SortNamedParamRector\Fixture;

function skip_sorted($foo = null, $bar = null, $baz = null) {}

skip_sorted(foo: $foo, bar: $bar);

skip_sorted($foo, bar: $bar, baz: $baz);
