<?php

namespace Rector\Core\Tests\Issues\Issue7306\Fixture;

class Fixture
{
    public function method1(int $value): void
    {
        if ($value) {
            // do something
        }
    }
    public function method2(): void
    {
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\Issue7306\Fixture;

class Fixture
{
    public function method1(int $value): void
    {
        if ($value !== 0) {
            // do something
        }
    }

    public function method2(): void
    {
    }
}

?>
