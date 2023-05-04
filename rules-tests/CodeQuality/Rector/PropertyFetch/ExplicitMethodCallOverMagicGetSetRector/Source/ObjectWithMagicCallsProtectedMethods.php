<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\PropertyFetch\ExplicitMethodCallOverMagicGetSetRector\Source;

use Nette\SmartObject;

final class ObjectWithMagicCallsProtectedMethods
{
    // adds magic __get() and __set() methods
    use SmartObject;

    private $name;

    protected function getName()
    {
        return $this->name;
    }

    protected function setName(string $name)
    {
        $this->name = $name;
    }
}
