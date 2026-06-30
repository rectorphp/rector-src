<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Source\SomeService;

final class StaticGetContainerGet extends TestCase
{
    /**
     * @var SomeService
     */
    private $someService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->someService = static::getContainer()->get(SomeService::class);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Source\SomeService;

final class StaticGetContainerGet extends TestCase
{
    private \Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Source\SomeService $someService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->someService = static::getContainer()->get(SomeService::class);
    }
}

?>
