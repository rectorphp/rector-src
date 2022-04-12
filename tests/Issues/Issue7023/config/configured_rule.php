<?php

declare(strict_types=1);

use Rector\Doctrine\Rector\Property\TypedPropertyFromToOneRelationTypeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(TypedPropertyFromToOneRelationTypeRector::class);
};
