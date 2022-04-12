<?php

declare(strict_types=1);

use Rector\Php73\Rector\BooleanOr\IsCountableRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(IsCountableRector::class);
};
