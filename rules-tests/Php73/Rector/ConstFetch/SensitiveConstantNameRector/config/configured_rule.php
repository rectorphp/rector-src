<?php

declare(strict_types=1);

use Rector\Php73\Rector\ConstFetch\SensitiveConstantNameRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SensitiveConstantNameRector::class);
};
