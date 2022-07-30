<?php

declare(strict_types=1);

use Rector\Set\ValueObject\SetList;
use Rector\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector;

return static function (Rector\Config\RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    $rectorConfig->rule(NormalizeNamespaceByPSR4ComposerAutoloadRector::class);
};
