<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php82\Rector\Class_\ReadOnlyClassRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ReadOnlyClassRector::class);

    $rectorConfig->phpVersion(PhpVersionFeature::READONLY_CLASS);
};
