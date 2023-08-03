<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

class SkipAssignPlusCallByRefGlobal {
    private array $fruits;
    
    public function __construct() {
        $this->fruits = ['banana', 'pear', 'apple'];
        \sort($this->fruits);
    }
}
