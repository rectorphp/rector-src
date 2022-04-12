<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenameClassRector::class)
        ->configure([
            'Some\\Random\\Class_' => 'Some\\Other\\Random\\Class_',
        ]);
};
