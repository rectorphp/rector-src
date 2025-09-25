<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\StrlenZeroToIdenticalEmptyStringRector\Fixture;

function (string $string) {
    strlen($string) < 0;
    0 > strlen($string);
};
