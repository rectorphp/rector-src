<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Closure\ClosureReturnTypeFromAssertInstanceOfRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ClosureReturnTypeFromAssertInstanceOfRector::class);
    $rectorConfig->phpVersion(PhpVersion::PHP_82);
};
