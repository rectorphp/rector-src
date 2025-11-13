<?php

namespace Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector\Source\SomeSetterClass;

final class SkipMocksOnVariable extends TestCase
{
    public function test()
    {
        $someVariable = $this->createMock(SomeSetterClass::class);
        $someVariable->expects($this->once())
            ->method('some')
            ->with(1);
    }
}
