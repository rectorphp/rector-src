<?php

declare(strict_types=1);

use Rector\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector;
use Rector\Carbon\Rector\FuncCall\TimeFuncCallToCarbonRector;
use Rector\Carbon\Rector\MethodCall\DateTimeMethodCallToCarbonRector;
use Rector\Carbon\Rector\New_\DateTimeInstanceToCarbonRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        DateFuncCallToCarbonRector::class,
        DateTimeInstanceToCarbonRector::class,
        DateTimeMethodCallToCarbonRector::class,
        TimeFuncCallToCarbonRector::class,
    ]);

    // Replace instaces where first arg is not a string
    $rectorConfig
        ->ruleWithConfiguration(RenameClassRector::class, [
            'DateTime' => 'Carbon\Carbon',
            'DateTimeImmutable' => 'Carbon\CarbonImmutable',
        ]);
};
