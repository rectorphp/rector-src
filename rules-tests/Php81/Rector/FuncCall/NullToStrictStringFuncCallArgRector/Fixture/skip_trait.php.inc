<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

trait SkipTrait
{
    public function getTitle()
    {
        if ($this->title === null) {
            return null;
        }

        return trim($this->title);
    }
}
