<?php

declare(strict_types=1);

use Rector\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector;
use Rector\PSR4\Rector\Namespace_\MultipleClassFileToPsr4ClassesRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(NormalizeNamespaceByPSR4ComposerAutoloadRector::class);
    $services->set(MultipleClassFileToPsr4ClassesRector::class);
};
