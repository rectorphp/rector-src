<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp74\Rector\Array_\DowngradeArraySpreadRector;
use Rector\DowngradePhp80\Rector\Class_\DowngradePropertyPromotionRector;
use Rector\DowngradePhp81\Rector\Property\DowngradeReadonlyPropertyRector;

return RectorConfig::configure()
    ->withRules([
        DowngradePropertyPromotionRector::class,
        DowngradeReadonlyPropertyRector::class,
        DowngradeArraySpreadRector::class,
    ]);
