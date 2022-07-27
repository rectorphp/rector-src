<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class SkipPropertyByVarDoc
{
    /** @var \Memcached */
    private $property;

    public function __construct()
    {
        if (class_exists(\Memcached::class)) {
            $this->property = new \Memcached();
        }
    }

    public function verify()
    {
        if ($this->property instanceof \Memcached) {
            return true;
        }

        return false;
    }
}