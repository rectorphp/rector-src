<?php

trait MaterializedPathEntity
{
    /** @var self */
    protected $parent;
    
    public function setParent(self $parent = null) : static
    {
        $this->parent = $parent;

        return $this;
    }
    
    public function getParent() : static
    {
        return $this->parent;
    }
}

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class DemoFile
{
    use MaterializedPathEntity;
    
    public function run()
    {
        return $this->getParent();
    }
}
?>
