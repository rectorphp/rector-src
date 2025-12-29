<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SortCallLikeNamedArgsRector\Fixture;

function skip_single_arg(?string $foo = null) {}

skip_single_arg(foo: 'test');
skip_single_arg();
