<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenameClassRector::class)
        ->call('configure', [[
            'old_2' => 'new_2',
        ]])
        ->call('configure', [[
            'old_4' => 'new_4',
        ]]);

    $containerConfigurator->import(__DIR__ . '/first_config.php');
    $containerConfigurator->import(__DIR__ . '/second_config.php');
};
