<?php

use PhpParser\Node\Scalar\String_ as Foo;
use PhpParser\Node\Stmt\Expression as Bar;

class NoNamespacedClassAliased
{
    public function run(): Foo
    {
        return new Foo('test');
    }
}

?>
-----
<?php

use PhpParser\Node\Scalar\String_ as Foo;

class NoNamespacedClassAliased
{
    public function run(): Foo
    {
        return new Foo('test');
    }
}

?>
