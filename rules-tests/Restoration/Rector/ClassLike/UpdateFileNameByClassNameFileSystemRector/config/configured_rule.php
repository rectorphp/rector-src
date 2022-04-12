<?php

declare(strict_types=1);

use Rector\Restoration\Rector\ClassLike\UpdateFileNameByClassNameFileSystemRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(UpdateFileNameByClassNameFileSystemRector::class);
};
