<?php

declare(strict_types=1);

use Rector\Php71\Rector\ClassConst\PublicConstantVisibilityRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(PublicConstantVisibilityRector::class);
};
