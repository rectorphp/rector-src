<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedStaticPropertyInBehatContextRectorTest\Fixture;

use Behat\Behat\Context\Context;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedStaticPropertyInBehatContextRectorTest\Source\OptionalBehatProperty;

final class SkipNonNullDefault implements Context
{
    /**
     * @var OptionalBehatProperty
     */
    public static $type = '';
}
