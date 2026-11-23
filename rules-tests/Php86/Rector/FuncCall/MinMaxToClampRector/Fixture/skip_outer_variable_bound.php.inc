<?php

namespace Rector\Tests\Php86\Rector\FuncCall\MinMaxToClampRector\Fixture;

fn (int $value, int $min) => max($min, min(100, $value));
fn (int $value, int $max) => min($max, max(0, $value));
