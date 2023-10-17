<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp81\Rector\StmtsAwareInterface\DowngradeSetAccessibleReflectionPropertyRector;
use Rector\DowngradePhp80\Rector\Expression\DowngradeMatchToSwitchRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
       DowngradeSetAccessibleReflectionPropertyRector::class,
       DowngradeMatchToSwitchRector::class,
    ]);
};
