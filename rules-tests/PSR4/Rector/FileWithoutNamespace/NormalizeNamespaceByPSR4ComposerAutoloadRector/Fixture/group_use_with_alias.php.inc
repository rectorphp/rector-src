<?php

declare(strict_types=1);

use Foo\Bar\{ SomeLongClass as SomeClass };
class AliasedUsed
{
    public function someMethod()
    {
        new SomeClass;
    }
}

?>
-----
<?php

namespace Rector\Tests\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector\Fixture;

use Foo\Bar\{SomeLongClass as SomeClass};
class AliasedUsed
{
    public function someMethod()
    {
        new \Foo\Bar\SomeLongClass;
    }
}
