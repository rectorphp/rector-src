<?php

declare(strict_types=1);

use Rector\Php53\Rector\Ternary\TernaryToElvisRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(TernaryToElvisRector::class);
};
