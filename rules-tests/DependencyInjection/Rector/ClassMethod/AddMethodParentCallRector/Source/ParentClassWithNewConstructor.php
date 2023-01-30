<?php

declare(strict_types=1);

namespace Rector\Tests\DependencyInjection\Rector\ClassMethod\AddMethodParentCallRector\Source;

class ParentClassWithNewConstructor
{
    /**
     * @var int
     */
    private $defaultValue;

    public function __construct()
    {
        $this->defaultValue = 5;
    }
}
