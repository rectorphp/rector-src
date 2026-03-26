<?php

namespace Rector\Tests\Php86\Rector\FuncCall\MinMaxToClampRector\Fixture;

fn (int $value, int $min, int $max) => max($min, max($max, $value));
fn (int $value, int $min, int $max) => min($min, min($max, $value));
