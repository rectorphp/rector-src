<?php

namespace Rector\Tests\CodeQuality\Rector\Include_\AbsolutizeRequireAndIncludePathRector\Fixture;

final class SkipAbsolutePaths
{
    public function run()
    {
        include "C:\\Users\\prj\\vendor\\autoload.php";
    }
}