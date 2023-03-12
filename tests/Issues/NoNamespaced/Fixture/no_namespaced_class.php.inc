<?php

use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;

class NoNamespacedClass
{
    public function run(): String_
    {
        return new String_('test');
    }
}

?>
-----
<?php

use PhpParser\Node\Scalar\String_;

class NoNamespacedClass
{
    public function run(): String_
    {
        return new String_('test');
    }
}

?>
