<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\Fixture;

abstract class SkipLateStaticBinding
{
    public const PRIMARY_KEY = 'id';

    public function insert(): void
    {
        $this->{static::PRIMARY_KEY} = 1;
    }
}
