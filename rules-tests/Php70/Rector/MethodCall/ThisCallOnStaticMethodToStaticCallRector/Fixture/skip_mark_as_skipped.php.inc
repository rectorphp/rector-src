<?php

namespace Rector\Tests\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipMarkAsSkipped extends TestCase
{
    public function testMe()
    {
        $this->markTestSkipped('whatever');
    }
}
