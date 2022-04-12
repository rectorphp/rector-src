<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\Concat\RemoveConcatAutocastRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveConcatAutocastRector::class);
};
