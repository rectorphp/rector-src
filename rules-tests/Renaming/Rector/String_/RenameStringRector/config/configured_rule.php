<?php

declare(strict_types=1);

use Rector\Renaming\Rector\String_\RenameStringRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenameStringRector::class)
        ->configure([
            'ROLE_PREVIOUS_ADMIN' => 'IS_IMPERSONATOR',
        ]);
};
