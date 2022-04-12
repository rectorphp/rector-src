<?php

declare(strict_types=1);

use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;
use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(JsonThrowOnErrorRector::class);
    $services->set(ChangeAndIfToEarlyReturnRector::class);
};
