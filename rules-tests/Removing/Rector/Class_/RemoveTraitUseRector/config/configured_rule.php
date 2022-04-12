<?php

declare(strict_types=1);

use Rector\Removing\Rector\Class_\RemoveTraitUseRector;
use Rector\Tests\Removing\Rector\Class_\RemoveTraitUseRector\Source\TraitToBeRemoved;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveTraitUseRector::class)
        ->configure([TraitToBeRemoved::class]);
};
