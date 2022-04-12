<?php

declare(strict_types=1);

use Rector\Php80\Rector\FuncCall\Php8ResourceReturnToObjectRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(Php8ResourceReturnToObjectRector::class);
};
