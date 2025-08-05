<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

use PHPUnit\Framework\TestCase;

final class ReturnInTestMethod extends TestCase
{
    public function testSomething()
    {
        return $this->getValue();
    }

    private function getValue(): int
    {
        return 123;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

use PHPUnit\Framework\TestCase;

final class ReturnInTestMethod extends TestCase
{
    public function testSomething(): int
    {
        return $this->getValue();
    }

    private function getValue(): int
    {
        return 123;
    }
}

?>
