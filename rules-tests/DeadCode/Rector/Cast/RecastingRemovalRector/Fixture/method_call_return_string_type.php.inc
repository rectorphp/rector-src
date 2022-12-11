<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Rector;

class MethodCallReturnStrictType
{
    public function run(): string
    {
        return (string) $this->getResult();
    }

    private function getResult(): string
    {
        return 'test';
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Rector;

class MethodCallReturnStrictType
{
    public function run(): string
    {
        return $this->getResult();
    }

    private function getResult(): string
    {
        return 'test';
    }
}

?>
