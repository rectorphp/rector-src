<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Source\SomeService;

final class SkipPublicProperty extends TestCase
{
    /**
     * @var SomeService
     */
    public $someService;

    protected function setUp(): void
    {
        $this->someService = static::getContainer()->get(SomeService::class);
    }
}
