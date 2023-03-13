<?php

use PhpParser\Node\Scalar as Foo;
use PhpParser\Node\Stmt\Expression as Bar;

class NoNamespacedClassAliasedPart
{
    public function run2(): Foo\String_
    {
        return new Foo\String_('test');
    }
}

?>
-----
<?php

use PhpParser\Node\Scalar as Foo;

class NoNamespacedClassAliasedPart
{
    public function run2(): Foo\String_
    {
        return new Foo\String_('test');
    }
}

?>
