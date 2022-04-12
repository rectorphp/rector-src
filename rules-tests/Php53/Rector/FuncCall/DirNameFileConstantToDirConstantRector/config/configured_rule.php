<?php

declare(strict_types=1);

use Rector\Php53\Rector\FuncCall\DirNameFileConstantToDirConstantRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DirNameFileConstantToDirConstantRector::class);
};
