<?php declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source;

class ParentWithParamWithDefaultValue
{
    public function execute($foo = true)
    {
    }
}
