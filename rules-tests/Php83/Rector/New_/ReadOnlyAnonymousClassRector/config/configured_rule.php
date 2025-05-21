<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\New_\ReadOnlyAnonymousClassRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ReadOnlyAnonymousClassRector::class);

    $rectorConfig->phpVersion(PhpVersionFeature::READONLY_ANONYMOUS_CLASS);
};
