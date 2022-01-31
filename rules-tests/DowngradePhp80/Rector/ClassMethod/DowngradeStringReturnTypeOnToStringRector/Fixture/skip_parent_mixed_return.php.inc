<?php

namespace Rector\Tests\DowngradePhp80\Rector\ClassMethod\DowngradeStringReturnTypeOnToStringRector\Fixture;

use Rector\Tests\DowngradePhp80\Rector\ClassMethod\DowngradeStringReturnTypeOnToStringRector\Source\ParentClassWithToString;

class SkipParentMixedReturn extends ParentClassWithToStringMixedReturn
{
    public function __toString()
    {
        return 'value';
    }
}

?>