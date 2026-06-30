<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Source\SomeService;

final class SkipNoTestCase
{
    /**
     * @var SomeService
     */
    private $someService;

    protected function setUp(): void
    {
        $this->someService = static::getContainer()->get(SomeService::class);
    }
}
