<?php declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source;

class ParentWithConstruct
{
    public function __construct($foo)
    {
    }
}
