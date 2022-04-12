<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);

    $services = $containerConfigurator->services();
    $services->set(RenameClassRector::class);
};
