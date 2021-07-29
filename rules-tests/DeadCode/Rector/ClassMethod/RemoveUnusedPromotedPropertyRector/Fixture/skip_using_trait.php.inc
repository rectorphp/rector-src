<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector\Fixture;

trait TraitA
{
    public function foo()
    {
        echo $this->usedDependency;
    }
}

class SkipUsingTrait
{
    use TraitA;

    public function __construct(
        private $usedDependency
    ) {
    }
}

?>
