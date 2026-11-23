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
        $object->a();
        $object->b();
    }
}

?>
