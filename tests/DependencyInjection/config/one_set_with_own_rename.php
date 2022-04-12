<?php

declare(strict_types=1);

use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $containerConfigurator->import(PHPUnitSetList::PHPUNIT_60);

    $services = $containerConfigurator->services();
    $services->set(RenameClassRector::class)
        ->configure([
            'Old' => 'New',
        ]);
};
