<?php

namespace Rector\Tests\Issues\Issue9646\Fixture;

echo array_key_exists(null, []);

class ParentClass
{
}

final class ChildClass extends ParentClass
{
    private $foo;

    public function __sleep() {
        return ['foo'];
    }

    public function run(string $name = null): string
    {
        return get_class() . ':' . get_parent_class();
    }

    public function runComplex(\PHPUnit\Framework\MockObject\MockObject&\stdClass $mockObject = null): string
    {
        return __METHOD__;
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\Issue9646\Fixture;

echo array_key_exists(null, []);

class ParentClass
{
}

final class ChildClass extends ParentClass
{
    private $foo;

    public function __sleep() {
        return ['foo'];
    }

    public function run(string $name = null): string
    {
        return get_class() . ':' . get_parent_class();
    }

    public function runComplex(\PHPUnit\Framework\MockObject\MockObject&\stdClass $mockObject = null): string
    {
        return __METHOD__;
    }
}

?>
