<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveAssignOfVoidReturnFunctionRector\Fixture;

class Fixture
{
    public function run()
    {
        $value = $this->getOne();
    }

    /**
     * @return void
     */
    private function getOne()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveAssignOfVoidReturnFunctionRector\Fixture;

class Fixture
{
    public function run()
    {
        $this->getOne();
    }

    /**
     * @return void
     */
    private function getOne()
    {
    }
}

?>
