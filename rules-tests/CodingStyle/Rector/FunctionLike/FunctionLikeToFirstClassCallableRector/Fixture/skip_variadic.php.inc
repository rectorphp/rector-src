<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

$fn = function (...$args) {
    return array_sum($args);
};

$fn(1, 2, 3);
