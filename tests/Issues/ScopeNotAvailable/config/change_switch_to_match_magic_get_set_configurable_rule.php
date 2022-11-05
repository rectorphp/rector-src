<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\PropertyFetch\ExplicitMethodCallOverMagicGetSetRector;
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Switch_\ChangeSwitchToMatchRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ChangeSwitchToMatchRector::class);
    $rectorConfig->rule(ExplicitMethodCallOverMagicGetSetRector::class);
};
