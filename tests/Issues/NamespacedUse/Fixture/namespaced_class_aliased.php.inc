<?php

namespace Rector\Core\Tests\Issues\NamespacedUse\Fixture;

use PhpParser\Node\Scalar\String_ as Foo;
use PhpParser\Node\Stmt\Expression as Bar;

class NamespacedClassAliased
{
    public function run(): Foo
    {
        return new Foo('test');
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\NamespacedUse\Fixture;

use PhpParser\Node\Scalar\String_ as Foo;

class NamespacedClassAliased
{
    public function run(): Foo
    {
        return new Foo('test');
    }
}

?>
