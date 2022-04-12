<?php

declare(strict_types=1);

use Rector\Php56\Rector\FuncCall\PowToExpRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(PowToExpRector::class);
};
