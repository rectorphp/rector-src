<?php

declare(strict_types=1);

use Rector\DowngradePhp81\Rector\FunctionLike\DowngradePureIntersectionTypeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradePureIntersectionTypeRector::class);
};
