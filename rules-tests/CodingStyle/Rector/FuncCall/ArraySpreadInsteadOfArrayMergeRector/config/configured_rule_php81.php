<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector;
use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersion::PHP_81);
    $rectorConfig->rule(ArraySpreadInsteadOfArrayMergeRector::class);
};
