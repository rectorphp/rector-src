<?php

namespace Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Fixture;

use Nette\Utils\FileSystem;

class InstantMakeInStaticMethod
{
    public static function run()
    {
        return FileSystem::write('file', 'content');
    }
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Fixture;

use Nette\Utils\FileSystem;

class InstantMakeInStaticMethod
{
    public static function run()
    {
        return (new \Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Source\TargetFileSystem())->dumpFile('file', 'content');
    }
}

?>
