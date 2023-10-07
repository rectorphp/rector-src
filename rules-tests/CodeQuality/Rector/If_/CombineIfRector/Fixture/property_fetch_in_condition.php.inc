<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CombineIfRector\Fixture;

class PropertyFetchInCondition
{
    private $prop;
    
    public function run()
    {
        if ($p = $this->prop->doSomething()) {
            if ($p) {
                return 'foo';
            }
        }
    }
}
?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CombineIfRector\Fixture;

class PropertyFetchInCondition
{
    private $prop;
    
    public function run()
    {
        if (($p = $this->prop->doSomething()) && $p) {
            return 'foo';
        }
    }
}
?>
