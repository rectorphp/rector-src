<?php

use PhpParser\Node\Scalar as Foo;
use PhpParser\Node\Stmt\Expression as Bar;

class NoNamespacedClassAliasedPartDocblock
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

use PhpParser\Node\Scalar as Foo;

class NoNamespacedClassAliasedPartDocblock
{
    /**
     * @param Foo\String_ $param
     */
    public function run2($param)
    {
    }
}

?>
