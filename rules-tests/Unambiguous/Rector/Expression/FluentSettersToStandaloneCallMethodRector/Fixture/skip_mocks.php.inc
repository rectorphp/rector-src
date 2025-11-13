<?php

namespace Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Source\SomeSetterClass;

final class SkipMocks extends TestCase
{
    public function test()
    {
        $this->createMock(SomeSetterClass::class)
            ->expects($this->once())
            ->method('some');
    }
}
