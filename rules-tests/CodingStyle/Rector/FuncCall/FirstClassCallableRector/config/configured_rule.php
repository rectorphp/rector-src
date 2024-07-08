<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\FuncCall\FirstClassCallableRector;
use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(FirstClassCallableRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_81);
};
