<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\Use_\RemoveUnusedAliasRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Use_\RemoveUnusedAliasRector\Source\DifferentNamespace as Foo;

class Demo
{
    public function run()
    {
        $foo = new Foo\StandaloneClass();
    }
}
?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\Use_\RemoveUnusedAliasRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Use_\RemoveUnusedAliasRector\Source\DifferentNamespace as Foo;

class Demo
{
    public function run()
    {
        $foo = new Foo\StandaloneClass();
    }
}
?>
