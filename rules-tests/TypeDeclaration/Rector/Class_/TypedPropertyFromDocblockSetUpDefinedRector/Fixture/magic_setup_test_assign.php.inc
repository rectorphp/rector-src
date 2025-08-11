<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromDocblockSetUpDefinedRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromDocblockSetUpDefinedRector\Source\SomeDocblockType;

final class MagicSetupTestAssign extends TestCase
{
    /**
     * @var SomeDocblockType
     */
    private $someType;

    protected function setUp(): void
    {
        $this->someType = $this->create('string');
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromDocblockSetUpDefinedRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromDocblockSetUpDefinedRector\Source\SomeDocblockType;

final class MagicSetupTestAssign extends TestCase
{
    private SomeDocblockType $someType;

    protected function setUp(): void
    {
        $this->someType = $this->create('string');
    }
}

?>
