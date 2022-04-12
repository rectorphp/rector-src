<?php

declare(strict_types=1);

use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenameFunctionRector::class)
        ->configure([
            'view' => 'Laravel\Templating\render',
            'sprintf' => 'Safe\sprintf',
        ]);
};
