<?php

declare(strict_types=1);

use Rector\DowngradePhp74\Rector\LNumber\DowngradeNumericLiteralSeparatorRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeNumericLiteralSeparatorRector::class);
};
