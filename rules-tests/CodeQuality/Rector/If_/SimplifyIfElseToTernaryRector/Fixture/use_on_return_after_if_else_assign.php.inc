<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector\Fixture;

class UseOnReturnAfterIfElseAssign
{
    public function run()
    {
        if (empty($value)) {
            $this->arrayBuilt[][$key] = true;
        } else {
            $this->arrayBuilt[][$key] = $value;
        }

        return $this->arrayBuilt[][$key];
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector\Fixture;

class UseOnReturnAfterIfElseAssign
{
    public function run()
    {
        $this->arrayBuilt[][$key] = empty($value) ? true : $value;

        return $this->arrayBuilt[][$key];
    }
}

?>
