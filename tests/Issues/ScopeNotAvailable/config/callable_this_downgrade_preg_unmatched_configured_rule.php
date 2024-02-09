<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp72\Rector\FuncCall\DowngradePregUnmatchedAsNullConstantRector;
use Rector\DowngradePhp81\Rector\FuncCall\DowngradeArrayIsListRector;

return RectorConfig::configure()->withRules(
    [DowngradeArrayIsListRector::class, DowngradePregUnmatchedAsNullConstantRector::class]
);
