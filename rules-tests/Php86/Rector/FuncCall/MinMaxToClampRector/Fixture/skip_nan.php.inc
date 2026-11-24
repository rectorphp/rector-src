<?php

namespace Rector\Tests\Php86\Rector\FuncCall\MinMaxToClampRector\Fixture;

fn (float $value) => max(NAN, min(5.0, $value));
fn (float $value) => max(0.0, min(NAN, $value));
