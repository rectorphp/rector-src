<?php

declare(strict_types=1);

namespace Rector\Tests\Strict\Rector\ClassMethod\AddConstructorParentCallRector\Source;

class ParentClassWithConstructor
{
    /**
     * @var int
     */
    private $defaultValue;

    public function __construct(private \stdClass $stdClass)
    {
        $this->defaultValue = 5;
    }
}
