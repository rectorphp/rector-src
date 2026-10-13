<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessUnionReturnDocblockRector\Fixture;

interface SomeStaticInterface
{
}

trait SkipStaticUnionTrait
{
    private $parent;

    /**
     * @return static|null
     */
    public function getParent(): ?SomeStaticInterface
    {
        return $this->parent;
    }

    /**
     * @return $this|null
     */
    public function getThisParent(): ?SomeStaticInterface
    {
        return $this->parent;
    }
}
