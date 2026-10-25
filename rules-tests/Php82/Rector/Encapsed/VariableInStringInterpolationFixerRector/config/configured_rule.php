<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php82\Rector\Encapsed\VariableInStringInterpolationFixerRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersion::PHP_82);

    $rectorConfig->rule(VariableInStringInterpolationFixerRector::class);
};
