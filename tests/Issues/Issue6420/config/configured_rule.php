<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\Expression\RemoveDeadStmtRector;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(RemoveDeadStmtRector::class);

    $services->set(RenameFunctionRector::class)
        ->configure([
            'preg_replace' => 'Safe\preg_replace',
        ]);
};
