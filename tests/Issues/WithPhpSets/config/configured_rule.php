<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPhpSets(php85: true)
    ->withPhpVersion(PhpVersion::PHP_85);