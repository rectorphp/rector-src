<?php

declare(strict_types=1);

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Source;

final class StaticFactory
{
    public $timestamp;

    public static function now()
    {
        return new static();
    }
}
