<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\ArrayKeysAndInArrayToArrayKeyExistsRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ArrayKeysAndInArrayToArrayKeyExistsRector::class);
};
