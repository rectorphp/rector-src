<?php

namespace Rector\Tests\Issues\GetValueMagicDir\Fixture;

class Fixture
{
    public function run()
    {
        strlen(__DIR__);
        strlen(__FILE__);

        new \Foo(['bar' => ['foo' => __DIR__ . '/bar']]);
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\GetValueMagicDir\Fixture;

class Fixture
{
    public function run()
    {
        'tests/Issues/GetValueMagicDir/Fixture';
        'tests/Issues/GetValueMagicDir/Fixture/fixture.php';

        ['bar' => ['foo' => 'tests/Issues/GetValueMagicDir/Fixture/bar']];
    }
}

?>
