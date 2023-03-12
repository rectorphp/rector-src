<?php

namespace Rector\Core\Tests\Issues\NamespacedUse\Fixture;

use PhpParser\Node\Scalar as Foo;
use PhpParser\Node\Stmt\Expression as Bar;

class NamespacedClassAliasedPart
{
    public function run2(): Foo\String_
    {
        return new Foo\String_('test');
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\NamespacedUse\Fixture;

use PhpParser\Node\Scalar as Foo;

class NamespacedClassAliasedPart
{
    public function run2(): Foo\String_
    {
        return new Foo\String_('test');
    }
}

?>
