<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\DirnameDirConcatStringToDirectStringPathRector\Fixture;

final class WindowsPaths
{
    public function run()
    {
        $path = dirname(__DIR__) . '\some\file.txt';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\DirnameDirConcatStringToDirectStringPathRector\Fixture;

final class WindowsPaths
{
    public function run()
    {
        $path = __DIR__ . '\..\some\file.txt';
    }
}

?>
