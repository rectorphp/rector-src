<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\Fixture;

#[\AllowDynamicProperties]
class SkipAllowDynamicAttribute
{
    public function set()
    {
        $this->value = 5;

        $this->value = 'hey';
    }
}