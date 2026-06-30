<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Source\SomeService;

final class SkipNotContainerFetch extends TestCase
{
    /**
     * @var SomeService
     */
    private $someService;

    protected function setUp(): void
    {
        $this->someService = new SomeService();
    }
}
