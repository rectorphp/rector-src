<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\IntvalToTypeCastRector\Fixture;

class InMulBinaryOp
{
    public function run()
    {
        $$name = GetTime(time()-86400*intval($FILTER[$bdays]));
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\IntvalToTypeCastRector\Fixture;

class InMulBinaryOp
{
    public function run()
    {
        $$name = GetTime(time()-86400*(int) $FILTER[$bdays]);
    }
}

?>
