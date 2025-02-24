<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Issues\GetValueMagicDir\Source\GetValueMagicDirRector;

return RectorConfig::configure()
    ->withRules([GetValueMagicDirRector::class]);
