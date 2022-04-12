<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\UnwrapSprintfOneArgumentRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(UnwrapSprintfOneArgumentRector::class);
};
