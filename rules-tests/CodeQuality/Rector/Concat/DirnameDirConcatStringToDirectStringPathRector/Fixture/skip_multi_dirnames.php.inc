<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\DirnameDirConcatStringToDirectStringPathRector\Fixture;

final class SkipMultiDirnames
{
    public function run()
    {
        $path = dirname(__DIR__, 3) . '/file.txt';
    }
}
