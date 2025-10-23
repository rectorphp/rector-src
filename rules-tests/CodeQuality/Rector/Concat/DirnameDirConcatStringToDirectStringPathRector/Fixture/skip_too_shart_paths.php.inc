<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\DirnameDirConcatStringToDirectStringPathRector\Fixture;

final class SkipTooShortPaths
{
    public function run()
    {
        $path = dirname(__DIR__) . 'file.txt';
    }
}
