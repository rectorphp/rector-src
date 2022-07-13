<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector\Fixture;

function () {
    if (rand(0, 1)) {
        return 1;
    }
    return 2;
};

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector\Fixture;

static function () {
    if (rand(0, 1)) {
        return 1;
    }
    return 2;
};

?>
