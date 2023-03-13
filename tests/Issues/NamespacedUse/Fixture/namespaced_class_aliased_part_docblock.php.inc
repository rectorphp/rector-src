<?php

namespace Rector\Core\Tests\Issues\NamespacedUse\Fixture;

use PhpParser\Node\Scalar as Foo;
use PhpParser\Node\Stmt\Expression as Bar;

class NamespacedClassAliasedPartDocblock
{
    /**
     * @param Foo\String_ $param
     */
    public function run2($param)
    {
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\NamespacedUse\Fixture;

use PhpParser\Node\Scalar as Foo;

class NamespacedClassAliasedPartDocblock
{
    /**
     * @param Foo\String_ $param
     */
    public function run2($param)
    {
    }
}

?>
