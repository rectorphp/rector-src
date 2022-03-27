<?php

namespace Rector\Tests\Php74\Rector\FuncCall\GetCalledClassToStaticClassRector\Fixture;

class SkipOnNonFinalClass
{
    public function callOnMe()
    {
        var_dump( get_called_class());
    }
}