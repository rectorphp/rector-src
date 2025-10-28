<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedStaticPropertyInBehatContextRectorTest\Fixture;

use Behat\Behat\Context\Context;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedStaticPropertyInBehatContextRectorTest\Source\OptionalBehatProperty;

final class SkipNonStatic implements Context
{
    /**
     * @var OptionalBehatProperty
     */
    public $type;
}
