<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PostRector\Rector\ClassRenamingPostRector;
use Rector\PSR4\Rector\FileWithoutNamespace\CollectPSR4ComposerAutoloadNamespaceRenamesRector;
use Rector\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector;
use Rector\PSR4\Rector\Namespace_\MultipleClassFileToPsr4ClassesRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(MultipleClassFileToPsr4ClassesRector::class);
    $rectorConfig->rule(CollectPSR4ComposerAutoloadNamespaceRenamesRector::class);
    $rectorConfig->rule(ClassRenamingPostRector::class);
    $rectorConfig->rule(NormalizeNamespaceByPSR4ComposerAutoloadRector::class);
};
