<?php

declare(strict_types=1);

use Rector\DowngradePhp70\Rector\Instanceof_\DowngradeInstanceofThrowableRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeInstanceofThrowableRector::class);
};
