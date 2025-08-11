<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromDocblockSetUpDefinedRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromDocblockSetUpDefinedRector\Source\SomeDocblockType;

final class SkipPublicProperty extends TestCase
{
    /**
     * @var SomeDocblockType
     */
    public $someType;

    protected function setUp(): void
    {
        $this->someType = $this->create('string');
    }
}
