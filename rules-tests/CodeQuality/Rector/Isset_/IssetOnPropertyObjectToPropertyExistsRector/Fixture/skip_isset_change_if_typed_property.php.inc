<?php

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

final class SkipIssetChangeIfTypedProperty
{
    private string $cachedBaseRoutePattern;

    public function getBaseRoutePattern(): string
    {
        if (isset($this->cachedBaseRoutePattern)) {
            return $this->cachedBaseRoutePattern;
        }
    }
}
