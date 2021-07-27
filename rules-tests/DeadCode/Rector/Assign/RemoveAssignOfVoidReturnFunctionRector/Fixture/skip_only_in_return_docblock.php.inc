<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveAssignOfVoidReturnFunctionRector\Fixture;

class SkipOnlyInReturnDocblock
{
    public function run()
    {
        $value = $this->getOne();
        return $value;
    }

    /**
     * @return void
     */
    protected function getOne()
    {
    }
}

class Extended extends SkipOnlyInReturnDocblock
{
    protected function getOne()
    {
        return 'test';
    }
}

echo (new Extended())->run();

?>
