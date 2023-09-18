<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenCollectorRector;
use Rector\TypeDeclaration\Collector\ParentClassCollector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(FinalizeClassesWithoutChildrenCollectorRector::class);

    $rectorConfig->collector(ParentClassCollector::class);
};
