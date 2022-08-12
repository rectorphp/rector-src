<?php

namespace Rector\Tests\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector\Fixture;

use Rector\Tests\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector\Source\BeLocalClass;

class SkipMultiLineAlreadyThis extends BeLocalClass
{
    public function run()
    {
        $this->assertThis(
            ["hls" => [], "hds" => [], "smooth" => [], "dash" => [], "progressive_download" => []],
            $this->sources->getSourcesByEpisode($episode)
        );
    }
}
