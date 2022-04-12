<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SeparateMultiUseImportsRector::class);
};
