<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector\Fixture;

final class RemoveOnlyTrailingParams
{
    public function __construct(
        private $firstUnusedDependency,
        private $usedDependency,
        private $secondUnusedDependency,
        private $thirdUnusedDependency
    ) {
    }

    public function get()
    {
        return $this->usedDependency;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector\Fixture;

final class RemoveOnlyTrailingParams
{
    public function __construct(
        $firstUnusedDependency,
        private $usedDependency
    ) {
    }

    public function get()
    {
        return $this->usedDependency;
    }
}

?>
