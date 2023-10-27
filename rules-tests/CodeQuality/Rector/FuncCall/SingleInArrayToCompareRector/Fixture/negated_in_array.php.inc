<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SingleInArrayToCompareRector\Fixture;

class NegatedInArray
{
    public function run()
    {
        $isIt = ! in_array(strtolower($type), ['$this'], true);
        $isIt = ! in_array(strtolower($type), ['$this']);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SingleInArrayToCompareRector\Fixture;

class NegatedInArray
{
    public function run()
    {
        $isIt = strtolower($type) !== '$this';
        $isIt = strtolower($type) != '$this';
    }
}

?>
