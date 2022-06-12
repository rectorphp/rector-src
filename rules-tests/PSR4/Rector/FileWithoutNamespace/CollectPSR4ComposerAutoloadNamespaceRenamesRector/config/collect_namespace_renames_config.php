<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PSR4\Rector\FileWithoutNamespace\CollectPSR4ComposerAutoloadNamespaceRenamesRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(CollectPSR4ComposerAutoloadNamespaceRenamesRector::class);
};
