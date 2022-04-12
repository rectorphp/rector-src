<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $services = $containerConfigurator->services();
    $services->set(ClassPropertyAssignToConstructorPromotionRector::class);
};
