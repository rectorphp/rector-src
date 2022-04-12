<?php

declare(strict_types=1);

use Rector\PSR4\Rector\Namespace_\MultipleClassFileToPsr4ClassesRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(MultipleClassFileToPsr4ClassesRector::class);
};
