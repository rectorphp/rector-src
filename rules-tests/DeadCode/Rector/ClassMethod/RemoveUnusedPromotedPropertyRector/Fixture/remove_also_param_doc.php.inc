<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector\Fixture;

class RemoveAlsoParamDoc
{
    /**
     * @param non-empty-string $someUnusedDependency
     */
    public function __construct(
        private $someUnusedDependency,
        private $usedDependency
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

class RemoveAlsoParamDoc
{
    public function __construct(
        private $usedDependency
    ) {
    }

    public function get()
    {
    	return $this->usedDependency;
    }
}

?>
