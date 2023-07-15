<?php

namespace Rector\Core\Tests\Issues\ExtendsNonReadOnlyClass\Fixture;

use Rector\Core\Tests\Issues\ExtendsNonReadOnlyClass\Source\ParentIsNonReadOnlyClass;

class Fixture extends ParentIsNonReadOnlyClass
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

use Rector\Core\Tests\Issues\ExtendsNonReadOnlyClass\Source\ParentIsNonReadOnlyClass;

final class Fixture extends ParentIsNonReadOnlyClass
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
