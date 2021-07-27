<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveAssignOfVoidReturnFunctionRector\Fixture;

class SkipOnlyInReturnDocblock
{
    public function run()
    {
        $value = $this->getOne();
    }

    /**
     * @return void
     */
    protected function getOne()
    {
    }
}

?>
