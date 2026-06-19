<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source;

final class SomeCacheProvider implements SomeCacheAdapterInterface
{
    public function clear(): bool
    {
        return true;
    }

    public function getCacheAdapter(): object
    {
        return $this;
    }
}
