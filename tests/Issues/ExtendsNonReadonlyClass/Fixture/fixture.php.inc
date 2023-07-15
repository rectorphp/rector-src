<?php

namespace Rector\Core\Tests\Issues\ExtendsNonReadOnlyClass\Fixture;

use Rector\Core\Tests\Issues\ExtendsNonReadOnlyClass\Source\NonReadonlyClass;

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

namespace Rector\Core\Tests\Issues\ExtendsNonReadOnlyClass\Fixture;

use Rector\Core\Tests\Issues\ExtendsNonReadOnlyClass\Source\NonReadonlyClass;

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
