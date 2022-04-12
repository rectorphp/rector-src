<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\FunctionLike\RemoveOverriddenValuesRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveOverriddenValuesRector::class);
};
