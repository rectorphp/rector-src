<?php

declare(strict_types=1);

use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ChangeAndIfToEarlyReturnRector::class);
};
