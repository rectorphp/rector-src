<?php

namespace Rector\Tests\Php86\Rector\FuncCall\MinMaxToClampRector\Fixture;

$limit = 100;

fn (int $value) => max(0, min($limit, $value));
fn (int $value) => max(0, min($value, $limit));
