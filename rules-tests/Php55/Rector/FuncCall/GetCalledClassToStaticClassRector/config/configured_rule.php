<?php

declare(strict_types=1);

use Rector\Php55\Rector\FuncCall\GetCalledClassToStaticClassRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(GetCalledClassToStaticClassRector::class);
};
