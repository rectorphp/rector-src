<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddOverrideAttributeToOverriddenMethodsRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_83);
};
