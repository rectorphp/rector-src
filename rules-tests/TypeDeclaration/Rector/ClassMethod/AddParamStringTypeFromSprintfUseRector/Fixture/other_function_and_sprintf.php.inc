<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

final class OtherFunctionAndSprintf
{
    public function test($name)
    {
        if (rand(0, 1)) {
            return trim($name);
        }

        return sprintf('Hello %s', $name);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

final class OtherFunctionAndSprintf
{
    public function test(string $name)
    {
        if (rand(0, 1)) {
            return trim($name);
        }

        return sprintf('Hello %s', $name);
    }
}

?>
