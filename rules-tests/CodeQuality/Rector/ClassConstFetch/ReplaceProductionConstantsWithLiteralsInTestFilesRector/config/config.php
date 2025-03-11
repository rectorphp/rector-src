<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassConstFetch\ReplaceProductionConstantsWithLiteralsInTestFilesRector;
use Rector\Config\RectorConfig;
use Rector\Tests\CodeQuality\Rector\ClassConstFetch\ReplaceProductionConstantsWithLiteralsInTestFilesRector\Fixture\AllowedConstant;

return RectorConfig::configure()
    ->withConfiguredRule(ReplaceProductionConstantsWithLiteralsInTestFilesRector::class, [
        'allowedPatterns' => ['*_allowed_pattern.php'],
        'allowedConstants' => [AllowedConstant::class],
    ]);
