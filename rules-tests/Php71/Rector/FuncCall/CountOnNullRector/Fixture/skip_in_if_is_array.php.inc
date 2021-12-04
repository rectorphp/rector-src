<?php

namespace Rector\Tests\Php71\Rector\FuncCall\CountOnNullRector\Fixture;

final class SkipInIfIsArray
{
    private $data;

    function countables()
    {
        if (is_array($this->data)) {
            echo count($this->data);
        }
    }
}
