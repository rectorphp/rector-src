<?php

declare(strict_types=1);

use Rector\DowngradePhp80\Rector\FuncCall\DowngradeStrEndsWithRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeStrEndsWithRector::class);
};
