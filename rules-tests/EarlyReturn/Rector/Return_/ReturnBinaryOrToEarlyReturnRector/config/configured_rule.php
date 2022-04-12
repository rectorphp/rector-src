<?php

declare(strict_types=1);

use Rector\EarlyReturn\Rector\Return_\ReturnBinaryOrToEarlyReturnRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ReturnBinaryOrToEarlyReturnRector::class);
};
