<?php

declare(strict_types=1);

namespace Rector\Tests\DependencyInjection\Rector\ClassMethod\AddMethodParentCallRector\Source;

class ParentClassWithNewConstructor
{
    /**
     * @var int
     */
    private $defaultValue = 5;

    public function __construct()
    {
    }
}
