<?php

namespace Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Fixture;

use Illuminate\Support\Facades\Response;
use Nette\Utils\FileSystem;

class InstantMakeInStaticMethod
{
    public static function run()
    {
        Response::view('example', ['new_example' => 123])->render();

        return FileSystem::write('file', 'content');
    }
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Fixture;

use Illuminate\Support\Facades\Response;
use Nette\Utils\FileSystem;

class InstantMakeInStaticMethod
{
    public static function run()
    {
        (new \Illuminate\Contracts\Routing\ResponseFactory())->view('example', ['new_example' => 123])->render();

        return (new \Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Source\TargetFileSystem())->dumpFile('file', 'content');
    }
}

?>
