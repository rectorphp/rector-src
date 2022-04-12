<?php

declare(strict_types=1);

use Rector\Php54\Rector\FuncCall\RemoveReferenceFromCallRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveReferenceFromCallRector::class);
};
