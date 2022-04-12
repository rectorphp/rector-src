<?php

declare(strict_types=1);

use Rector\Php73\Rector\FuncCall\SensitiveDefineRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SensitiveDefineRector::class);
};
