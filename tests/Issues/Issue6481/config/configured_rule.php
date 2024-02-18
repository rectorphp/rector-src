<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\RemoveDeadInstanceOfRector;

return RectorConfig::configure()
    ->withRules([EncapsedStringsToSprintfRector::class, RemoveDeadInstanceOfRector::class]);
