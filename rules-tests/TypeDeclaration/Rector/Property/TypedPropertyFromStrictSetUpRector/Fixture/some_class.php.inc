<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictSetUpRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SomeClass extends TestCase
{
    private $value;

    public function setUp()
    {
        $this->value = 1000;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictSetUpRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SomeClass extends TestCase
{
    private int $value;

    public function setUp()
    {
        $this->value = 1000;
    }
}

?>
