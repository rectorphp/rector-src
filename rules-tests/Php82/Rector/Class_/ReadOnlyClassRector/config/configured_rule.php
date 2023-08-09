<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Php82\Rector\Class_\ReadOnlyClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ReadOnlyClassRector::class);

    $rectorConfig->phpVersion(PhpVersionFeature::READONLY_CLASS);
};
