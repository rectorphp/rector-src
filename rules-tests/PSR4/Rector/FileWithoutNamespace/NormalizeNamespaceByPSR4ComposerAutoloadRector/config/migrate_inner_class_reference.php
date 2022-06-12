<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/normalize_namespace_without_namespace_config.php');

    $rectorConfig->ruleWithConfiguration(NormalizeNamespaceByPSR4ComposerAutoloadRector::class, [
        NormalizeNamespaceByPSR4ComposerAutoloadRector::MIGRATE_INNER_CLASS_REFERENCE => true
    ]);
};
