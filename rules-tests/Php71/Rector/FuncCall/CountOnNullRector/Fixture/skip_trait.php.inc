<?php

namespace Rector\Tests\Php71\Rector\FuncCall\CountOnNullRector\Fixture;

trait SkipTrait
{
    public function run()
    {
        return count($this->getItems());
    }
}
