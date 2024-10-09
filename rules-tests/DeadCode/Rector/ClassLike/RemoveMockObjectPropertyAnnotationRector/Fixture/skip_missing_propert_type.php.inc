<?php

namespace Rector\Tests\DeadCode\Rector\ClassLike\RemoveMockObjectPropertyAnnotationRector\Fixture;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class SkipMissingPropertyType extends TestCase
{
    /**
     * @var SomeClass|MockObject
     */
    private $someProperty;
}
