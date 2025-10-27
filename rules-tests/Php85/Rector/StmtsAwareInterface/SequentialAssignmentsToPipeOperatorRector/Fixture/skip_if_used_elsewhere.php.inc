<?php

namespace Rector\Tests\Php85\Rector\StmtsAwareInterface\SequentialAssignmentsToPipeOperatorRector\Fixture;

$value = "hello world";
$result1 = function3($value);
$result2 = function2($result1);

$result3 = $result1 + 100;
