<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp80\Rector\Expression\DowngradeMatchToSwitchRector;
use Rector\DowngradePhp81\Rector\StmtsAwareInterface\DowngradeSetAccessibleReflectionPropertyRector;

return RectorConfig::configure()
    ->withRules([DowngradeSetAccessibleReflectionPropertyRector::class, DowngradeMatchToSwitchRector::class]);
