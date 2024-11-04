<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\FuncCall\RemoveGetClassGetParentClassNoArgsRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RemoveGetClassGetParentClassNoArgsRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_83);
};
