<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Source\SomeService;

final class SkipNoDocblock extends TestCase
{
    private $someService;

    protected function setUp(): void
    {
        $this->someService = static::getContainer()->get(SomeService::class);
    }
}
