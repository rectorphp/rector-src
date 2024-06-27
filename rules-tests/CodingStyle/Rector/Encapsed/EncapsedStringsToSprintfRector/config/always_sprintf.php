<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(EncapsedStringsToSprintfRector::class, [
            EncapsedStringsToSprintfRector::ALWAYS => true,
        ]);
};
