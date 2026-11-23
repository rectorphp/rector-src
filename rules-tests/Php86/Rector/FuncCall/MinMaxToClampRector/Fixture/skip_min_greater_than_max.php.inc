<?php

namespace Rector\Tests\Php86\Rector\FuncCall\MinMaxToClampRector\Fixture;

fn (int $value) => max(100, min(0, $value));
fn (int $value) => min(0, max(100, $value));
fn (float $value) => max(0.0, min(NAN, $value));
