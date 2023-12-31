<?php

namespace Rector\Tests\Issues\NamespacedUse\Fixture;

use PhpParser\Node\Scalar\String_ as Foo;
use PhpParser\Node\Stmt\Expression as Bar;

class NamespacedClassAliasedDocblock
{
    /**
     * @param Foo $param
     */
    public function run($param)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\NamespacedUse\Fixture;

use PhpParser\Node\Scalar\String_ as Foo;

class NamespacedClassAliasedDocblock
{
    /**
     * @param Foo $param
     */
    public function run($param)
    {
    }
}

?>
