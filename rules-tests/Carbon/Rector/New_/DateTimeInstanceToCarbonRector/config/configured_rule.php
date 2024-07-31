<?php

declare(strict_types=1);

use Rector\Carbon\Rector\New_\DateTimeInstanceToCarbonRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DateTimeInstanceToCarbonRector::class);
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
            'DateTime' => 'Carbon\Carbon',
            'DateTimeImmutable' => 'Carbon\CarbonImmutable',
        ]);
};
