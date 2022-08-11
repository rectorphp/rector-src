<?php
declare(strict_types=1);

namespace Rector\Tests\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector\Source;

abstract class ClassThatWillChangeReturnType
{
    public function changeMyReturn()
    {
    }
}
