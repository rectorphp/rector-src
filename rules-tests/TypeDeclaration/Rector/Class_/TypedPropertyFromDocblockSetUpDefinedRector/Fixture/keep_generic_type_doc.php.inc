<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromDocblockSetUpDefinedRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromDocblockSetUpDefinedRector\Source\SomeDocblockType;

final class GenericTypeDoc extends TestCase
{
    /**
     * @var \Iterator<SomeType>
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

final class GenericTypeDoc extends TestCase
{
    /**
     * @var \Iterator<SomeType>
     */
    private \Iterator $someType;

    protected function setUp(): void
    {
        $this->someType = $this->create('string');
    }
}

?>