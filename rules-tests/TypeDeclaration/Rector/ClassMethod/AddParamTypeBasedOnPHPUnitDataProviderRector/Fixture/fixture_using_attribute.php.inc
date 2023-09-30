<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use Iterator;
use PHPUnit\Framework\TestCase;

final class SomeTestWithDataProvider extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('provideData')]
    public function test_with_attribute($name)
    {
    }

    public function provideData(): Iterator
    {
        yield ['some'];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use Iterator;
use PHPUnit\Framework\TestCase;

final class SomeTestWithDataProvider extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('provideData')]
    public function test_with_attribute(string $name)
    {
    }

    public function provideData(): Iterator
    {
        yield ['some'];
    }
}

?>
