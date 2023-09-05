<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use Iterator;
use PHPUnit\Framework\TestCase;

final class ReturnArray extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('provideData')]
    public function test_with_attribute($name, $number)
    {
    }

    public function provideData()
    {
        return [['some', 100]];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use Iterator;
use PHPUnit\Framework\TestCase;

final class ReturnArray extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('provideData')]
    public function test_with_attribute(string $name, int $number)
    {
    }

    public function provideData()
    {
        return [['some', 100]];
    }
}

?>
