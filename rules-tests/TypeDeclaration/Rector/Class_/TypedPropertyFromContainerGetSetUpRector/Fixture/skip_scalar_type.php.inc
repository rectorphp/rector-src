<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipScalarType extends TestCase
{
    /**
     * @var string
     */
    private $value;

    protected function setUp(): void
    {
        $this->value = static::getContainer()->get('some_parameter');
    }
}
