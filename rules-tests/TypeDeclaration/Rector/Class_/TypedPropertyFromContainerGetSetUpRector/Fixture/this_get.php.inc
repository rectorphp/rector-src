<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Source\SomeService;

final class ThisGet extends TestCase
{
    /**
     * @var SomeService
     */
    private $someService;

    protected function setUp(): void
    {
        $this->someService = $this->get(SomeService::class);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Source\SomeService;

final class ThisGet extends TestCase
{
    private \Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Source\SomeService $someService;

    protected function setUp(): void
    {
        $this->someService = $this->get(SomeService::class);
    }
}

?>
