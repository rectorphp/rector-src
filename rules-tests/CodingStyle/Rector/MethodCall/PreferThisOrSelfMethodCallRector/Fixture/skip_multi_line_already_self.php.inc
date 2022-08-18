<?php

namespace Rector\Tests\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector\Fixture;

use Rector\Tests\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector\Source\SomeAbstractTestCase;

class SkipMultiLineAlreadySelf extends SomeAbstractTestCase
{
    public function run()
    {
        self::assertSame(
            ["hls" => [], "hds" => [], "smooth" => [], "dash" => [], "progressive_download" => []],
            $this->sources->getSourcesByEpisode($episode)
        );
    }
}