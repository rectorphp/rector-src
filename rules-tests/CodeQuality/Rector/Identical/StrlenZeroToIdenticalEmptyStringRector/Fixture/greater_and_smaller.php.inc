<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\StrlenZeroToIdenticalEmptyStringRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Identical\StrlenZeroToIdenticalEmptyStringRector\Source\Stringable;

function (string $string, $mixed, Stringable $stringable) {
    strlen($string) > 0;
    0 < strlen($string);

    strlen($mixed) > 0;
    0 < strlen($mixed);

    strlen($stringable) > 0;
    0 < strlen($stringable);
};

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\StrlenZeroToIdenticalEmptyStringRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Identical\StrlenZeroToIdenticalEmptyStringRector\Source\Stringable;

function (string $string, $mixed, Stringable $stringable) {
    $string !== '';
    $string !== '';

    (string) $mixed !== '';
    (string) $mixed !== '';

    (string) $stringable !== '';
    (string) $stringable !== '';
};

?>
