<?php

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\ReplaceProductionConstantsWithLiteralsInTestFilesRector\Fixture;

final class FixArrayConstant extends \PHPUnit\Framework\TestCase
{
    public function baz(): void
    {
        $foo = SomeDisallowedArrayConstant::FOO;
    }
}

final class SomeDisallowedArrayConstant {
    public const FOO = ['1', 2, false];
}
?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\ReplaceProductionConstantsWithLiteralsInTestFilesRector\Fixture;

final class FixArrayConstant extends \PHPUnit\Framework\TestCase
{
    public function baz(): void
    {
        $foo = ['1', 2, false];
    }
}

final class SomeDisallowedArrayConstant {
    public const FOO = ['1', 2, false];
}
?>
