<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Source\InterfaceWithConstruct;

abstract class SkipImplementInterfaceConstruct implements InterfaceWithConstruct {
    protected $name;

    public function __construct(string $name, array $config) {
        $this->name = $name;
    }
}

?>
