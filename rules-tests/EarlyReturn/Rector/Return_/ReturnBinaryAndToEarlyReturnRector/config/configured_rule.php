<?php

declare(strict_types=1);

use Rector\EarlyReturn\Rector\Return_\ReturnBinaryAndToEarlyReturnRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ReturnBinaryAndToEarlyReturnRector::class);
};
