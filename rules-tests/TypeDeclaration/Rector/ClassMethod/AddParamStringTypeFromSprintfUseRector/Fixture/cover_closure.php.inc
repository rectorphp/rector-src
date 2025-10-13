<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

final class CoverClosure
{
    public function run()
    {
        $someClosure = function ($name) {
            return sprintf('Hello %s', $name);
        };

    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

final class CoverClosure
{
    public function run()
    {
        $someClosure = function (string $name) {
            return sprintf('Hello %s', $name);
        };

    }
}

?>
