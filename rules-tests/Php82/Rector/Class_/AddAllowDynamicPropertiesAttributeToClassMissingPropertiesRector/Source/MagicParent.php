<?php

declare(strict_types=1);

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Source;

class MagicParent
{
    public function __set($key, $value)
    {
    }

    public function __get($key)
    {
    }
}
