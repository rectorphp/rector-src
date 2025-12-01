<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SortNamedParamRector\Fixture;

function skip_single_arg(?string $foo = null) {}

skip_single_arg(foo: 'test');
skip_single_arg();
