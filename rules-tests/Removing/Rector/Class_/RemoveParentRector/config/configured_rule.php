<?php

declare(strict_types=1);

use Rector\Removing\Rector\Class_\RemoveParentRector;
use Rector\Tests\Removing\Rector\Class_\RemoveParentRector\Source\ParentTypeToBeRemoved;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveParentRector::class)
        ->configure([ParentTypeToBeRemoved::class]);
};
