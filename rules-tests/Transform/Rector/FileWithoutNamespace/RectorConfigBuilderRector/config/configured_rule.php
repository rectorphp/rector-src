<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\FileWithoutNamespace\RectorConfigBuilderRector;

return RectorConfig::configure()
    ->withFluentCallNewLine()
    ->withRules([RectorConfigBuilderRector::class]);
