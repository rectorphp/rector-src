<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

final class PassSprintf
{
    public function test($name)
    {
        return sprintf('Hello %s', $name);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

final class PassSprintf
{
    public function test(string $name)
    {
        return sprintf('Hello %s', $name);
    }
}

?>
