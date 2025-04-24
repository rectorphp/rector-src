<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Source;

class SomeOtherClass
{
    public function __construct(public mixed $value) {}
}
