<?php

namespace Rector\Core\Tests\Issues\ExtendsNotReadOnlyClass\Fixture;

use Rector\Core\Tests\Issues\ExtendsNotReadOnlyClass\Source\ParentIsNonReadOnlyClass;

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

namespace Rector\Core\Tests\Issues\ExtendsNotReadOnlyClass\Fixture;

use Rector\Core\Tests\Issues\ExtendsNotReadOnlyClass\Source\ParentIsNonReadOnlyClass;

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
