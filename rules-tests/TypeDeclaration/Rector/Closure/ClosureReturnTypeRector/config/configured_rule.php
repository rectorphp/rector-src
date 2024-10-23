<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ClosureReturnTypeRector::class);
    $rectorConfig->phpVersion(PhpVersion::PHP_82);
};
