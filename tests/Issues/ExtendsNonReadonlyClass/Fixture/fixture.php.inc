<?php

namespace Rector\Core\Tests\Issues\ExtendsNonReadonlyClass\Fixture;

use Rector\Core\Tests\Issues\ExtendsNonReadonlyClass\Source\NonReadonlyClass;

class Fixture extends NonReadonlyClass
{
    public function __construct(
        private string $foo
    ) {}

    public function run(): string
    {
        return $this->foo;
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\ExtendsNonReadonlyClass\Fixture;

use Rector\Core\Tests\Issues\ExtendsNonReadonlyClass\Source\NonReadonlyClass;

final class Fixture extends NonReadonlyClass
{
    public function __construct(
        private readonly string $foo
    ) {}

    public function run(): string
    {
        return $this->foo;
    }
}

?>
