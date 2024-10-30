<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\FuncCall\RemoveGetParentClassNoArgsRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RemoveGetParentClassNoArgsRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_83);
};
