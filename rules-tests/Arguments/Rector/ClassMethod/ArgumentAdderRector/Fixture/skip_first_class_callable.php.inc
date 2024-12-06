<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeContainerBuilder;

class SkipFirstClassCallable
{
    public function create()
    {
        $containerBuilder = new SomeContainerBuilder();
        $containerBuilder->addCompilerPass(...);
    }
}
