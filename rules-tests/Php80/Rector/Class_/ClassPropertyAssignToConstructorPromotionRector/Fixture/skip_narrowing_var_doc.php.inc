<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeCacheAdapterInterface;
use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeCacheProvider;

final class SkipNarrowingVarDoc
{
    /**
     * @var SomeCacheProvider
     */
    private SomeCacheAdapterInterface $cacheProvider;

    public function __construct(SomeCacheAdapterInterface $cacheProvider)
    {
        $this->cacheProvider = $cacheProvider;
    }

    public function run(): object
    {
        return $this->cacheProvider->getCacheAdapter();
    }
}
