<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Source;

abstract class AbstractParentWithFilledProperty
{
    protected array $allowedGrantTypes = [];

    public function __construct()
    {
        $this->allowedGrantTypes[] = 'authorization_code';
    }
}
