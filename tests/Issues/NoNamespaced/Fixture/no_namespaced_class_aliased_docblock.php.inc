<?php

use PhpParser\Node\Scalar\String_ as Foo;
use PhpParser\Node\Stmt\Expression as Bar;

class NoNamespacedClassAliasedDocblock
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

use PhpParser\Node\Scalar\String_ as Foo;

class NoNamespacedClassAliasedDocblock
{
    /**
     * @param Foo $param
     */
    public function run($param)
    {
    }
}

?>
