<?php

declare(strict_types=1);

use Rector\Core\Tests\PhpUnit\MultipleFilesChangedTrait\Rector\Class_\CreateJsonWithNamesForClassRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(CreateJsonWithNamesForClassRector::class);
};
