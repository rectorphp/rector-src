<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector\Fixture;

function () {
    $b += 1;
    return $b;
};

function () {
    $e /= 1;
    return $e;
};

function () {
    $f **= 1;
    return $f;
};

function () {
    $m .= 1;
    return $m;
};
