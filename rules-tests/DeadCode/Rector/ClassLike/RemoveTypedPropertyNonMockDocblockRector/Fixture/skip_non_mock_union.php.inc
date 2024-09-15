<?php

namespace Rector\Tests\DeadCode\Rector\ClassLike\RemoveTypedPropertyNonMockDocblockRector\Fixture;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\Source\stdClass;
use Rector\Tests\DeadCode\Rector\ClassLike\RemoveTypedPropertyNonMockDocblockRector\Source\RealType;

final class SkipNonMockUnion extends TestCase
{
    /**
     * @var RealType|stdClass
     */
    private RealType $someProperty;
}
