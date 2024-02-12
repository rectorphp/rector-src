<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\AddTestsVoidReturnTypeWhereNoReturnRector\Fixture;

use PHPUnit\Framework\TestCase;

class SomeClass extends TestCase
{
    public function testSomething()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\AddTestsVoidReturnTypeWhereNoReturnRector\Fixture;

use PHPUnit\Framework\TestCase;

class SomeClass extends TestCase
{
    public function testSomething(): void
    {
    }
}

?>
