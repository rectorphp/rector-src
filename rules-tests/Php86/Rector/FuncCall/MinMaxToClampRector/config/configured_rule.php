<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php86\Rector\FuncCall\MinMaxToClampRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(MinMaxToClampRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_86);
};
