<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\FunctionLike\RemoveCodeAfterReturnRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveCodeAfterReturnRector::class);
};
