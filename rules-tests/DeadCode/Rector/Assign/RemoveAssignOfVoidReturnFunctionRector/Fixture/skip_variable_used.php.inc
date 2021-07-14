<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveAssignOfVoidReturnFunctionRector\Fixture;

class SkipVariableUsed
{
    public function run()
    {
        if (rand(0, 1)) {
            $value = $this->getOne();
        } else {
            $value = 1;
        }

        return $value;
    }

    public function run2($value = 1)
    {
        if (rand(0, 1)) {
            $value = $this->getOne();
        }

        return compact('value');
    }

    private function getOne(): void
    {
    }
}

?>
