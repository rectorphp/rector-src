<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\FuncCall\ConsistentImplodeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ConsistentImplodeRector::class);
};
