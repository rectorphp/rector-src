<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyUselessVariableRector\Fixture;

function & () {
    $var = 26;
    return $var;
};
