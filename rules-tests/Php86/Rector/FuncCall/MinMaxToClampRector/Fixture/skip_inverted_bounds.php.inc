<?php

namespace Rector\Tests\Php86\Rector\FuncCall\MinMaxToClampRector\Fixture;

fn (int $value) => max(10, min(5, $value));
fn (int $value) => min(5, max(10, $value));
