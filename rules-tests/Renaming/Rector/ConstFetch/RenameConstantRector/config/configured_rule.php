<?php

declare(strict_types=1);

use Rector\Renaming\Rector\ConstFetch\RenameConstantRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenameConstantRector::class)
        ->configure([
            'MYSQL_ASSOC' => 'MYSQLI_ASSOC',
            'OLD_CONSTANT' => 'NEW_CONSTANT',
        ]);
};
