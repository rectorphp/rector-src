<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php82\Rector\New_\FilesystemIteratorSkipDotsRector;

return RectorConfig::configure()
    ->withRules([FilesystemIteratorSkipDotsRector::class]);
