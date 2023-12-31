<?php

namespace Rector\Tests\Issues\DoubleRun\Fixture;

final class RemoveAll
{
    public function __construct(
        private $unusedPropety
    ) {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\DoubleRun\Fixture;

final class RemoveAll
{
}

?>
