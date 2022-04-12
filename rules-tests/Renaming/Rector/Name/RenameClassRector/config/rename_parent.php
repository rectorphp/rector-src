<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenameClassRector::class)
        ->configure([
            'ThisClassDoesNotExistAnymore' => 'NewClassThatDoesNotExistEither',
            'App\NotHereClass\AndNamespace' => 'NewClassThatDoesNotExistEither',
        ]);
};
