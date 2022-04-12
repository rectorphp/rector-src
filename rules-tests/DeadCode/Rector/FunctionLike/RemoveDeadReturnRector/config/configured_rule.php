<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\FunctionLike\RemoveDeadReturnRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveDeadReturnRector::class);
};
