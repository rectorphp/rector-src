<?php

declare(strict_types=1);

use Rector\Php81\Rector\FuncCall\Php81ResourceReturnToObjectRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(Php81ResourceReturnToObjectRector::class);
};
