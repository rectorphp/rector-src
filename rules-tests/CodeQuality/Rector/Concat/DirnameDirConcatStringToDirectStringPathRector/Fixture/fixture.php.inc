<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\DirnameDirConcatStringToDirectStringPathRector\Fixture;

final class Fixture
{
    public function run()
    {
        $path = dirname(__DIR__) . '/file.txt';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\DirnameDirConcatStringToDirectStringPathRector\Fixture;

final class Fixture
{
    public function run()
    {
        $path = __DIR__ . '/../file.txt';
    }
}

?>
