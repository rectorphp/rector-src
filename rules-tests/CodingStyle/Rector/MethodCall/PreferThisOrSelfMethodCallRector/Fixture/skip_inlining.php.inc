<?php

namespace Rector\Tests\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector\Fixture;

use Rector\Tests\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector\Source\SomeAbstractTestCase;

final class SkipInlining extends SomeAbstractTestCase
{
    public function run()
    {
        $items = [];

        self::assertSame(
            ['hls' => []],
            $items
        );
    }
}
