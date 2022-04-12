<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveUnusedPrivatePropertyRector::class)
        ->configure([
            RemoveUnusedPrivatePropertyRector::REMOVE_ASSIGN_SIDE_EFFECT => false,
        ]);
};
