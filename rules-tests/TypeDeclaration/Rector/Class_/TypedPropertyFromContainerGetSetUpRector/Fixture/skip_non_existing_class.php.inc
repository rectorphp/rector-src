<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipNonExistingClass extends TestCase
{
    /**
     * @var \Some\Non\Existing\Service
     */
    private $someService;

    protected function setUp(): void
    {
        $this->someService = static::getContainer()->get('some_service');
    }
}
