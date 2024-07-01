<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector\FixtureDeadStmtUnusedVariable;

class X
{
    public function f()
    {
        $var = $object->a() . $object->b();
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector\FixtureDeadStmtUnusedVariable;

class X
{
    public function f()
    {
        $object->b();
        $object->a();
    }
}

?>
