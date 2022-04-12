<?php

declare(strict_types=1);

use Rector\Php70\Rector\FuncCall\CallUserMethodRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(CallUserMethodRector::class);
};
