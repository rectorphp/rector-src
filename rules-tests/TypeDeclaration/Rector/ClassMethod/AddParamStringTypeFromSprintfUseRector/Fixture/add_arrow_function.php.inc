<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

final class AddArrowFunction
{
    public function test()
    {
        $someClosure = fn ($name) => sprintf('Hello %s', $name);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

final class AddArrowFunction
{
    public function test()
    {
        $someClosure = fn (string $name) => sprintf('Hello %s', $name);
    }
}

?>
