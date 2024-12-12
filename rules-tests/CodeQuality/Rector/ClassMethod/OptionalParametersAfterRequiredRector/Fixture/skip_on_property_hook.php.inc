<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipOnPropertyHook
{
    public array $cookies {
        get => get(CookieManager::class)->all();
    }
}
