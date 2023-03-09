<?php

declare(strict_types=1);

namespace JMS\Serializer\Annotation;

if (class_exists('JMS\Serializer\Annotation\ExclusionPolicy')) {
    return;
}

#[\Attribute(\Attribute::TARGET_CLASS)]
final class ExclusionPolicy
{
    public function __construct($values = [], ?string $policy = null)
    {
    }
}
