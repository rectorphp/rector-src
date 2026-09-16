<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPhpVersion(PhpVersion::PHP_80)
    ->withRules([TernaryToNullsafeCoalesceRector::class]);
