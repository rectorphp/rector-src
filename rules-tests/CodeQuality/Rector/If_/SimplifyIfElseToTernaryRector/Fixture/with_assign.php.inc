<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector\Fixture;

class WithAssign
{
    public function run()
    {
        if ($a = $this->methodX()) {
            $this->out = $a;
        } else {
            $this->out = $this->methodY();
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector\Fixture;

class WithAssign
{
    public function run()
    {
        $this->out = ($a = $this->methodX()) ? $a : $this->methodY();
    }
}

?>
