<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Source\AbstractParentWithFilledProperty;

final class SkipPropertyFilledInParentConstructor extends AbstractParentWithFilledProperty
{
    protected array $allowedGrantTypes;

    public function __construct()
    {
        parent::__construct();

        $this->allowedGrantTypes = ['refresh_token'];
    }
}
