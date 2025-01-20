<?php

namespace Rector\Tests\CodeQuality\Rector\Include_\AbsolutizeRequireAndIncludePathRector\Fixture;

final class SkipWindowsAbsolutePaths
{
    public function local()
    {
        include "C:\\Users\\prj\\vendor\\autoload.php";
        include "C:/Users/prj/vendor/autoload.php";
    }

    public function network()
    {
        include "\\share\\network\\vendor\\autoload.php";
    }
}