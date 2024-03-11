<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToCoalescingRector\Fixture;

class SkipThrowAfterForeach
{
    public const SHOPCONFIGKEY_FILTER = 'MY_KEY';

    /**
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function getFilterConfig(): array
    {
        $shopConfig = clxMobileNet::config('shopconfig');

        foreach ($shopConfig as $key => $mixedValues) {
            if (self::SHOPCONFIGKEY_FILTER === $key) {
                return $mixedValues;
            }
        }

        throw new \Exception('Usage of "'.self::class.'" assumes that ShopConfig "'.self::SHOPCONFIGKEY_FILTER.'" is defined!');
    }
}